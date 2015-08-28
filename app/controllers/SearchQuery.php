<?php

class SearchQuery
{
    public $fromDate;
    public $toDate;
    public $searchBy;
    public $client_id;
    public $mediaType;
    public $media_id;
    public $title;
    public $tendency;
    public $source;
    public $show;
    public $gender;
    public $description;

    public function __construct(array $data)
    {
        $today = (new DateTime())->format('d/m/Y');
        $this->fromDate = isset($data['fromDate']) ? $data['fromDate'] : $today;
        $this->toDate = isset($data['toDate']) ? $data['toDate'] : $today;
        $this->searchBy = isset($data['searchBy']) ? $data['searchBy'] : 'published';
        $this->client_id = isset($data['client_id']) ? $data['client_id'] : null;
        $this->mediaType = isset($data['mediaType']) ? $data['mediaType'] : false;
        $this->media_id = isset($data['media_id']) ? $data['media_id'] : false;
        $this->title = isset($data['title']) ? $data['title'] :false;
        $this->tendency = isset($data['tendency']) ? $data['tendency'] :false;
        $this->source = isset($data['source']) ? $data['source'] :false;
        $this->show = isset($data['show']) ? $data['show'] :false;
        $this->gender = isset($data['gender']) ? $data['gender'] :false;
        $this->description = isset($data['description']) ? $data['description'] :false;
    }
}
