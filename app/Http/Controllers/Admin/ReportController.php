<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\TransactionType;
use App\Http\Controllers\Controller;
use App\Services\VirtualViewService; // サービスをインポート

class ReportController extends Controller
{
    protected $viewService;

    // コンストラクタでサービスを注入
    public function __construct(VirtualViewService $viewService)
    {
        $this->viewService = $viewService;
    }

    /**
     * 月間サマリーレポートを表示
     */
    public function monthlySummary()
    {
        // サービスからデータを取得
        $monthlySummary = $this->viewService->getMonthlySummary(); 
        
        // 'admin/report/monthly_summary.blade.php' にデータを渡して返す
        return view('admin.report.monthly_summary', compact('monthlySummary'));
    }

    /**
     * 勘定科目別サマリーレポートを表示
     */
    public function categorySummary(){
        
        // ----------------------------------------------------
        // 1. フィルター用データを取得
        // ----------------------------------------------------
        // 取引タイプ：フォームのセレクトボックスに必要
        $transactionTypes = TransactionType::all();
        
        // カテゴリーリスト：フォームのセレクトボックスに必要
        // 関連する transaction_type_id を data-type 属性に設定するためにロード
        $categoriesList = Category::with('transaction_type')->get();

        // ユーザーリスト：ユーザー選択ボックスに必要 (適切なスコープで取得してください)
        // 今回は全ユーザーを想定
        $users = User::all(); 


        // ----------------------------------------------------
        // 2. レポートデータを取得し、フィルタを適用
        // ----------------------------------------------------
        $request = request(); // リクエストオブジェクトを取得
        
        // フィルター引数を準備
        $userId = $request->input('user_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $transactionTypeId = $request->input('transaction_type_id');
        $categoryId = $request->input('category_id');

        // VirtualViewServiceのメソッド呼び出しを更新し、すべてのフィルター引数を渡す
        // 注: VirtualViewService の getCategorySummary メソッドも、これらの引数を受け入れるように更新する必要があります。
        // 今回は、以前提供されたメソッドシグネチャ (userId, startDate, endDate) に合わせて呼び出しを調整します。
        
        $categorySummary = $this->viewService->getCategorySummary(
            $userId,
            $startDate,
            $endDate
            // もしサービス内で取引タイプやカテゴリーによるフィルタリングも行う場合は、
            // サービスメソッドのシグネチャに追加して渡す必要があります。
        ); 


        // ----------------------------------------------------
        // 3. ビューへデータを渡す
        // ----------------------------------------------------
        return view('admin.report.category_summary', compact(
            'categorySummary', 
            'transactionTypes',  // 👈 追加：Undefined variable エラーを解決
            'categoriesList',    // 👈 追加：カテゴリーフィルター用
            'users'              // 👈 追加：ユーザーフィルター用
        ));
    }
}