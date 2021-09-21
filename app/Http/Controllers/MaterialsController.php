<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\ChainRepositoryInterface;
use App\Enroll;
use App\Material;
use Illuminate\Support\Facades\Auth;
use App\Lesson;
use App\Level;
use App\Classes;
use App\Paginate;
use DB;
use App\SecondaryChain;
use Carbon\Carbon;
use Modules\UploadFiles\Entities\file;
use Modules\UploadFiles\Entities\media;
use Modules\UploadFiles\Entities\page;

class MaterialsController extends Controller
{
    protected $chain;

    public function __construct(ChainRepositoryInterface $chain)
    {
        $this->chain = $chain;
        $this->middleware(['permission:material/get' , 'ParentCheck'],   ['only' => ['index']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,$count = null)
    {
    
        $request->validate([
            'year' => 'exists:academic_years,id',
            'type' => 'exists:academic_types,id',
            'level' => 'exists:levels,id',
            'segment' => 'exists:segments,id',
            'courses'    => 'nullable|array',
            'courses.*'  => 'nullable|integer|exists:courses,id',
            'sort_in' => 'in:asc,desc',
            'item_type' => 'string|in:page,media,file',
            'class' => 'nullable|integer|exists:classes,id',
            'lesson' => 'nullable|integer|exists:lessons,id' 
        ]);

        if($request->user()->can('site/show-all-courses')){//admin
            $lessons = $this->chain->getEnrollsByChain($request);
        }

        if(!$request->user()->can('site/show-all-courses')){//enrolled users
            $lessons = $this->chain->getEnrollsByChain($request)->where('user_id',Auth::id());
        }
        
        $lessons = $lessons->with('SecondaryChain')->get()->pluck('SecondaryChain.*.lesson_id')->collapse();  

        if($request->has('lesson')){
            if(!in_array($request->lesson,$lessons->toArray()))
                return response()->json(['message' => __('messages.error.no_active_for_lesson'), 'body' => []], 400);

            $lessons = [$request->lesson];
        }
            
        $material = Material::with(['lesson','course.attachment'])->whereIn('lesson_id',$lessons);

        if($request->user()->can('site/course/student'))
            $material->where('visible',1)->where('publish_date' ,'<=', Carbon::now());

        $sort_in = 'desc';
        if($request->has('sort_in'))
            $sort_in=$request->sort_in;

        $material->orderBy('created_at',$sort_in);

        //copy this counts to count it before filteration
        $query=clone $material;
        $all=$query->select(DB::raw
                        (  "COUNT(case `type` when 'file' then 1 else null end) as file ,
                            COUNT(case `type` when 'media' then 1 else null end) as media ,
                            COUNT(case `type` when 'page' then 1 else null end) as page" 
                        ))->first()->only(['file','media','page']);
        $cc['all']=$all['file']+$all['media']+$all['page'];
        //

        if($request->has('item_type'))
            $material->where('type',$request->item_type);

        if($count == 'count'){

            $counts = $material->select(DB::raw
                (  "COUNT(case `type` when 'file' then 1 else null end) as file ,
                    COUNT(case `type` when 'media' then 1 else null end) as media ,
                    COUNT(case `type` when 'page' then 1 else null end) as page" 
                ))->first()->only(['file','media','page']);
            $counts['all']=$cc['all'];

            return response()->json(['message' => __('messages.materials.count'), 'body' => $counts], 200);
        }

        $AllMat=$material->with(['lesson.SecondaryChain.Class'])->get();
        foreach($AllMat as $one){
            $one->class = $one->lesson->SecondaryChain->pluck('class')->unique();
            $one->level = Level::whereIn('id',$one->class->pluck('level_id'))->get();
            unset($one->lesson->SecondaryChain);
        }

        return response()->json(['message' => __('messages.materials.list'), 'body' => $AllMat->paginate(Paginate::GetPaginate($request))], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $material = Material::find($id);

        if(!isset($material))
            return response()->json(['message' => __('messages.error.not_found'), 'body' => null], 400);

        if(!isset($material->getOriginal()['link']))
            return response()->json(['message' => 'No redirection link', 'body' => null], 400);

        if(isset($material->getOriginal()['link'])){

            $url = $material->getOriginal()['link'];

            if(str_contains($material->getOriginal()['link'],'youtube') && $material->media_type != 'Link'){
                if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i',$material->getOriginal()['link'], $match)){
                    $url = 'https://www.youtube.com/embed/'.$match[1];
                }
            }
            return redirect($url);
        }
        
    }

    public function Material_Details(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:materials,id',
        ]);

        $material = Material::find($request->id);
       
        if(!isset($material))
            return response()->json(['message' => __('messages.error.not_found'), 'body' => null], 400);

        if($material->type == 'file')
            $result = file::find($material->item_id);

        if($material->type == 'media')
            $result = media::find($material->item_id);
        
        if($material->type == 'page')
            $result = page::find($material->item_id);

            return response()->json(['message' => __('messages.materials.list'), 'body' => $result], 200);        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
