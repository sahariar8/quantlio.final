<?php

namespace App\Http\Controllers;
use App\Models\TestDetail;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TestDetailsController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    function __construct()
    {
       // $this->middleware('permission:testDetails|insert-testDetails|edit-testDetails|delete-testDetails', ['only' => ['index']]);
       $this->middleware('permission:insert-testDetails', ['only' => ['create']]);
       $this->middleware('permission:edit-testDetails', ['only' => ['edit','update']]);
       $this->middleware('permission:delete-testDetails', ['only' => ['destroy']]);
    }
   /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function index(){
       $data = TestDetail::all();
       return view('testDetails', ['tests' => $data])->with('i');
    }
   
    public function create(Request $request){
       $validator = Validator::make($request->all(),[
           'test_name' => 'required|unique:test_details,test_name',
           'class' => 'required',
           'description' => 'required',
           'parent' => 'required',
           'metabolite' => 'required',
       ]);
       if($validator->fails()){
           return redirect('/testDetails')->with('validation_error', ' ')->withErrors($validator)->withInput();
       }else{
           $test = new TestDetail;
           $test->test_name = $request->input('test_name');
           $test->class = $request->input('class');
           $test->description = $request->input('description');
           $test->parent = $request->input('parent');
           $test->metabolite= $request->input('metabolite');
           if ($test->save()) {
               return redirect('/testDetails')->with('save_success', 'Test details added successfully.');
           } else {
               return redirect('/testDetails')->with('save_error', 'There is some problem while save!');
           }
       }
    } 

    public function edit($id){
       $test = TestDetail::find($id);
       return response()->json([
           'status' => 200,
           'test' => $test,
       ]);
    }

    public function update(Request $request,$id){
      $validator = Validator::make($request->all(),[
           'test_name' => 'required|unique:test_details,test_name,'.$id,
           'class' => 'required',
           'description' => 'required',
           'parent' => 'required',
           'metabolite' => 'required',
       ]);
       if($validator->fails()){
           return redirect('/testDetails')->with('validation_error', $id)->withErrors($validator)->withInput();
       }else {
           $testId  = $request->input('edit_id');
           $test = TestDetail::find($testId);
           $test->test_name = $request->input('test_name');
           $test->class = $request->input('class');
           $test->description = $request->input('description');
           $test->parent = $request->input('parent');
           $test->metabolite = $request->input('metabolite');
           if ($test->update()) {
               return redirect('/testDetails')->with('save_success', 'Test details Updated successfully.');
           } else {
               return redirect('/testDetails')->with('save_error', 'There is some problem while save!');
           }
        }
    } 
   
    public function destroy(Request $request){
       $testId  = $request->input('delete_test_id');
       $test = TestDetail::find($testId);
       $test->delete();

       return redirect()->back()->with('status','Test deleted successfully');
    } 
}