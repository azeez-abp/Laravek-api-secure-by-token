<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class ImageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        //  return parent::toArray($request);

        return [
            'image_index' => $this->id, //$this is the mode
            'name'      => $this->name,
            'original' => URL(\str_replace('\\', '/', $this->path)),
            'resize'   => URL(\str_replace('\\', '/', $this->output_path)),
            'image_user' => $this->user_id,
            'image_album' => $this->album_id,
            'issue_date' => $this->created_at,

        ];
    }
}
