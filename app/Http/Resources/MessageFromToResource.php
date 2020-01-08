<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Message;
use  App\User;
use Illuminate\Support\Facades\Auth;

class MessageFromToResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */

    public function toArray($request)
    {
        $session_id = Auth::User()->id;
        $from = User::find($this->From);
        $from->picture = $from->attachment->path;
        $To = User::find($this->To);
        $To->picture = $To->attachment->path;
        $imageCollection = collect([
            'jpg','JPG',
            'jpeg','JPEG',
            'png','PNG',
            'gif','GIF'
        ]);

        $fileCollection = collect([
            'pdf','PDF',
            'docx','DOCX',
            'doc','DOC',
            'xls','XLS',
            'xlsx','XLSX',
            'ppt','PPT',
            'pptx','PPTX',
            'zip','ZIP',
            'rar','RAR',
        ]);

        $videoCollection = collect([
            'mp4','MP4',
            'avi','AVI',
            'flv','FLV',
        ]);

        $audioCollection = collect([
            'mp3','MP3',
            'ogg','OGG',
            'ogv','OGV',
            'oga','OGA',
            'wav','WAV',
        ]);

        if(isset($this->attachment)){
            $extension = $this->attachment->extension;
            if ($imageCollection->contains($extension)) {
                $type = 'image';
            }
            else if($fileCollection->contains($extension)){
                $type = 'file';
            }
            else if($videoCollection->contains($extension)){
                $type = 'video';
            }
            else if($audioCollection->contains($extension)){
                $type = 'audio';
            }
            else{
                $type = null;
            }
        }
        else{
            $extension = null;
            $type = 'text';
        }
      $about = User::find($this->about);
        $about->picture = $about->attachment->path;
        $arr = [
            'id' => $this->id,
            'Message' => $this->text,
            'about' => $about,
            'From' => $from,
            'To' => $To,
            'Seen'=>$this->seen,
            'file' => $this->file,
            'type' => $type,
            'deleted' => $this->deleted
        ];
        if(isset($this->attachment))
        {
            $arr['extension']=$this->attachment->extension;
            // $arr['name']=pathinfo($this->attachment->name->getClientOriginalName(), PATHINFO_FILENAME);
        }

        if ($this->deleted == 0) {
            return $arr;
        } elseif ($this->deleted == Message::$DELETE_FROM_ALL) {
            $arr['Message'] = "this message was Deleted for All";
            return $arr;
        } elseif ($this->deleted == Message::$DELETE_FOR_RECEIVER && $session_id == $this->To) {
            $arr['Message'] = "this message was Deleted";
            return $arr;
        } elseif ($this->deleted == Message::$DELETE_FOR_RECEIVER && $session_id == $this->From) {
            return $arr;
        } elseif ($this->deleted == Message::$DELETE_FOR_SENDER && $session_id == $this->From) {
            $arr['Message'] = "this message was Deleted";
            return $arr;
        } elseif ($this->deleted == Message::$DELETE_FOR_SENDER && $session_id == $this->To) {
            return $arr;
        }
    }
}
