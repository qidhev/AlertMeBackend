<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateNotification;
use App\Models\City;
use App\Models\Notification;
use App\Models\TypeLocation;
use App\Models\TypeNotification;
use App\Modules\MqttModule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
//        throw new \Exception('Join', 403);

        return Inertia::render('Notification/Index', [
            'notifications' => Notification::all(),
            'cities' => City::all(),
            'types' => TypeNotification::all(),
            'typesLocation' => TypeLocation::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('Notification/Create', [
            'cities' => City::all(),
            'types' => TypeNotification::all(),
            'typesLocation' => TypeLocation::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateNotification $request): RedirectResponse
    {
        $data = $request->getData();

        if (empty($data['type_name']) && empty($data['type_id'])) {
            throw new \Exception("Не заполнена информация о тип уведомления");
        }

        if (!empty($data['type_name'])) {
            $type = TypeNotification::create(['name' => $data['type_name']]);
            $data['type_id'] = $type->id;
            unset($data['type_name']);
        }

        Notification::create($data);

        return to_route('notification.index');
    }

    public function sendNotification(Request $request)
    {
        $notification = Notification::find($request->integer('id'));

        if ($notification->send) {
            throw new \Exception('Данное уведомление уже было отправлено');
        }

        $topic = $notification->getTopic();

        $message = [
            'notification' => $notification,
            'history' => $notification->parent_id ? $notification->history() : []
        ];

        $notificationSend = $notification->send;
        $notificationSendAt = $notification->sent_at;

        try {
            $notification->send = true;
            $notification->sent_at = new \DateTime();
            $notification->save();

            $mqttModule = (new MqttModule(env('MQTT_HOST'), env('MQTT_PORT')))->connect();

            $mqttModule->sendMessage($topic, json_encode($message));

            return to_route('notification.index');

        } catch (\Exception $exception) {
            $notification->send = $notificationSend;
            $notification->sent_at = $notificationSendAt;
            $notification->save();
            throw $exception;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Notification $notification): Response
    {
        return Inertia::render('Notification/Show', [
            'notification' => $notification
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Notification $notification)
    {
        //
    }
}
