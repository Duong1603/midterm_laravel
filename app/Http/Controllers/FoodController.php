<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\T_food;

class FoodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $foods= T_food::all();
        if (count($foods) > 0) {
            return response()->json(["status" =>"200", "success" => true, "count" => count($foods), "data" => $foods]);
        } else {
            return response()->json(["status" => "failed", "success" => false, "message" => "Whoops! no record found"]);
        }
    }

     
    public function searchByName(Request $request)
    {
        $result = DB::table('t_foods')
        ->whereBetween('price', [$request->min, $request->max])->orWhere('name', 'like', '%'. $request->name .'%')
        ->get();
        return response()->json(["data"=>   $result]);
    }

    public function searchSum(){
        $sum = DB::table('t_foods')->select(DB::raw('type,count(type) as quantity'))->groupBy('type')->get();
        if(count($sum) > 0) {
            return response()->json(["status" => "200", "success" => true, "count" => count( $sum), "data" =>  $sum]);
        }
        else {
            return response()->json(["status" => "failed", "success" => false, "message" => "Whoops! no record found"]);
        }
    }

    // public function searchSum()
    // {
    //     $count_category = DB::table('t_foods')
    //         ->selectRaw('t_foods.type, count(*) as total')->get();
    //     return response()->json($count_category, Response::HTTP_OK);     
    // }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        {
            $validation = Validator::make(
                $request->all(),
                [
                    'detail' => 'required',
                    'name' => 'required',
                    'price' => 'required',
                    'image' => 'mimes:jpeg,jpg,png,gif|max:20000'
                ]
            );

            if ($validation->fails()) {
                $response = array('status' => 'error', 'errors' => $validation->errors()->toArray());
                return response()->json($response);
            }
            $name = '';

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $name = time() . '_' . $file->getClientOriginalName();
                $destinationPath = public_path('image'); //project\public\car, //public_path(): trả về đường dẫn tới thư mục public
                $file->move($destinationPath, $name); //lưu hình ảnh vào thư mục public/images/cars
            }

            $food = new T_food();
            $food->detail = $request->detail;
            $food->name = $request->name;
            $food->price = $request->price;
            $food->image = $name;
            $food->save();
            if ($food) {
                return response()->json(["status" => "200", "success" => true, "message" => "food record created successfully", "data" => $food]);
            } else {
                return response()->json(["status" => "failed", "success" => false, "message" => "Whoops! failed to create."]);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $food = T_food::find($id);
        return view('detail',['food'=>$food]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        // $name = '';
        
        // if($request -> hasFile('image')){
        //     $this->validate($request,[
        //         'image' =>'mimes:jpg,png,jpeg|max:4000',
        //     ],[
        //         'image.mimes'=>'Chỉ chấp nhận files ảnh',
        //         'image.max' => 'Chỉ chấp nhận files ảnh dưới 2Mb',

        //     ]);
        //     $file =$request ->file(('image'));
        //     $name = time().'_'.$file->getClientOriginalName();
        //     $destinationPath=public_path('image');
        //     $file -> move($destinationPath, $name);
        // }
        // $this->validate($request,[
        //     'name'=>'required', 
        //     'price'=>'required',
        // ],[
        //     'name.required' =>'Bạn chưa nhập mô tả',
        //     'price.required' =>'Bạn chưa nhập model',
        // ]);
        // $food= T_food::find();
        // $food->name=$request->name;
        // $food->price=$request->price;
        // $food->detail=$request->detail;
        // $food->image=$name;
        // $food->save();
    
        // return redirect()->route('food.index')->with('success', 'Bạn đã thêm thành công');
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
