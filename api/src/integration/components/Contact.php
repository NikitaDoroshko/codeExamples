<?php

namespace app\src\integration\components;

use app\models\Model;
use app\src\integration\models\ContactModel;


class Contact extends AmoComponent
{
    protected string $modelClass = 'app\src\integration\models\ContactModel';

    public function get($contactId): Model
    {
        $response = $this->getResponse('GET', '/api/v4/contacts/'.$contactId);
        return $this->getModel($response);
    }

    public function linkChat($data): Model
    {
        $response = $this->getResponse('POST', '/api/v4/contacts/chats', $data);
        return $this->getModel($response);
    }

    public function updateContact($data): Model
    {
        $response = $this->getResponse('PATCH', '/api/v4/contacts', $data);
        return $this->getModel($response);
    }

    public function updateNotes($data, $entity, $id): Model
    {
        $url = sprintf('/api/v4/contacts/%s/notes/%s', $entity, $id);
        $response = $this->getResponse('PATCH', $url, $data);
        return $this->getModel($response);
    }

    public function addNotes($data): Model
    {
        $response = $this->getResponse('POST', '/api/v4/contacts/notes', $data);
        return $this->getModel($response);
    }

    public function getChatList($contactId): Model
    {
        $url = '/api/v4/contacts/chats?contact_id='.$contactId;
        $response = $this->getResponse('GET', $url);
        return $this->getModel($response);
    }

    public function createContact($data): Model
    {
        $response = $this->getResponse('POST', '/api/v4/contacts', $data);
        return $this->getModel($response);
    }

    public function filterContactByPhone($query): ContactModel
    {
        $response = $this->getResponse(
            'GET',
            sprintf('/api/v4/contacts%s', '?filter[custom_fields_values][phone][]='.urlencode($query))
        );
        return $this->getModel($response);
    }

    public function getContactList($query)
    {
        $response = $this->getResponse('GET', sprintf('/api/v4/contacts?query=%s&with=leads,customers', $query));
        return $this->getModel($response);
    }

}