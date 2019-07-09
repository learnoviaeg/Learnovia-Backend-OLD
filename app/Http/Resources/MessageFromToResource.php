<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use  App\User;

class MessageFromToResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $from=User::find($this->From);
        $To=User::find($this->To);

        return[
            'id' =>$this->id,
            'Message' =>$this->text ,
            'Child'=>$this->about,
            'From'=>$from->name,
            "To"=>$To->name

        ];    }
}
