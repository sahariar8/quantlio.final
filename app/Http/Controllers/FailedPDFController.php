<?php
namespace App\Http\Controllers;
use App\Models\OrderCodeQueue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\PDFGenerationService;

class FailedPDFController extends Controller
{
  protected PDFGenerationService  $_pdfGenerationService;

    function __construct()
     {
        $this->_pdfGenerationService = new PDFGenerationService();

         // Add your middleware logic here
        //  $this->middleware('pdelete-failed-ordersermission:insert-trades', ['only' => ['create']]);
        //  $this->middleware('permission:edit-trades', ['only' => ['edit', 'update']]);
         $this->middleware('permission:delete.failed-pdf', ['only' => ['destroy']]);
     }

    public function index()
    {
        // $filteredRecords = OrderCodeQueue::where('numberOfFailur', '>=', $this->_pdfGenerationService->get_env_TRY_IF_FAILED_TIMES())->get();
        $filteredRecords = DB::table('order_code_queues')->where('numberOfFailur', '>=', $this->_pdfGenerationService->get_env_TRY_IF_FAILED_TIMES())->get();
        return view('failed-pdf', ['trades' => $filteredRecords])->with('i');
    }

    // public function edit(ordercodequeue $tradesFilter)
    // {
    //     return view('trades_filter.edit',['trades' => $tradesFilter]);

    // }

    // public function update(Request $request, ordercodequeue $tradesFilter)
    // {
    //     $data = $request->validate([
    //         'generic'=>'required|string',
    //         'brand'=>'required|string',
    //     ]);
    //     $tradesFilter->update($data);
    //     return redirect()->route('trades')->with('success','Trades Updated Successfully');
    // }

    // public function destroy(OrderCodeQueue $tradesFilter)
    // {
    //     $tradesFilter->delete();
    //     return redirect()->route('trades')->with('success', 'Trades Deleted Successfully');
    // }

    public function destroy(Request $request){
        
        // $testId = $request->input('delete_test_id');
        // $test   = DB::table('order_code_queues')->find($testId);
        // $test->delete();
        $testId = $request->input('delete_test_id');

        DB::table('order_code_queues')
        ->where('id', $testId)
        ->delete();
 
        return redirect()->back()->with('status','Test deleted successfully');
     } 
}