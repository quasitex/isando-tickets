<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repository\NotificationRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\Notification;
use Illuminate\Http\UploadedFile;

class NotificationController extends Controller
{
    protected $notificationRepo;

    public function __construct(NotificationRepository $notificationRepository)
    {
        $this->notificationRepo = $notificationRepository;
    }

    public function get(Request $request)
    {
        return self::showResponse(true, $this->notificationRepo->getTemplatesInCompanyContext($request));
    }

    public function add(Request $request)
    {
        $notification = $this->notificationRepo->createTemplate($request['entity_id'], $request['entity_type'], $request['notification_type_id'], $request['name'], $request['description'], $request['text'], $request['priority']);
        return self::showResponse(true, $notification);
    }

    public function edit(Request $request, $id)
    {
        $notification = $this->notificationRepo->updateTemplate($id,$request['notification_type'], $request['name'], $request['description'], $request['text'], $request['priority']);
        return self::showResponse(true, $notification);
    }

    public function delete($id)
    {
        return self::showResponse($this->notificationRepo->deleteTemplate($id));
    }

    public function find($id)
    {
        return self::showResponse(true, $this->notificationRepo->findTemplate($id));
    }

    public function send(Request $request)
    {
        $attachments = [];
        foreach ($request->files as $file) {
            $attachments[] = [
                'data' => $file->get(),
                'name' => $file->getClientOriginalName()
            ];
        }

        $x = [
            'subject' => $request['subject'],
            'body' => $request['body'],
            'recipients' => $request['recipients'],
            'attachments'=>$attachments
        ];
        return self::showResponse(true, $x);

        Mail::send(new Notification($request['recipients'], $request['subject'], $request['body'], $attachments));
        return self::showResponse(true);
    }

    public function getTypes()
    {
        return self::showResponse(true, $this->notificationRepo->getTypesInCompanyContext());
    }

    public function addType(Request $request)
    {
        $type = $this->notificationRepo->createType($request->name, $request->name_de, $request->icon);
        return self::showResponse(true, $type);
    }

    public function updateType(Request $request, $id)
    {
        $type = $this->notificationRepo->updateType($id, $request->name, $request->name_de, $request->icon);
        return self::showResponse(true, $type);
    }

    public function deleteType($id)
    {
        return self::showResponse($this->notificationRepo->deleteType($id));
    }
}