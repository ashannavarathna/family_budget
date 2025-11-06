<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\TransactionType; // Import the TransactionType model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator; // Import Validator

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        // Eager load transactionType to prevent N+1 query issues in the index view
        $categories = Category::with('transaction_type')->get();
        return view('admin.category.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     */
    public function create()
    {
        $transactionTypes = TransactionType::all();
        return view('admin.category.create', compact('transactionTypes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'transaction_type_id' => 'required|exists:transaction_types,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            Category::create($request->all());

            return redirect()->route('admin.category.index')
                ->with('success', '勘定科目を正常に作成しました。');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', '勘定科目の作成中にエラーが発生しました: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\models\Category  $category
     *
     */
    public function show(Category $category)
    {
        // シンプルな管理画面では、通常showはeditへリダイレクトします
        return redirect()->route('admin.category.edit', $category);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\models\Category  $category
     *
     */
    public function edit(Category $category)
    {
        $transactionTypes = TransactionType::all();
        return view('admin.category.edit', compact('category', 'transactionTypes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\models\Category  $category
     *
     */
    public function update(Request $request, Category $category)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'transaction_type_id' => 'required|exists:transaction_types,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $category->update($request->all());

            return redirect()->route('admin.category.index')
                ->with('success', '勘定科目を正常に更新しました。');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', '勘定科目の更新中にエラーが発生しました: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\models\Category  $category
     *
     */
    public function destroy(Category $category)
    {
        try {
            // Check for related transactions before deleting
            if ($category->transactions()->count() > 0) {
                return redirect()->route('admin.category.index')
                    ->with('error', '関連する取引が存在するため、この勘定科目を削除できません。');
            }

            $category->delete();

            return redirect()->route('admin.category.index')
                ->with('success', '勘定科目を正常に削除しました。');

        } catch (\Exception $e) {
            return redirect()->route('admin.category.index')
                ->with('error', '勘定科目の削除中にエラーが発生しました: ' . $e->getMessage());
        }
    }
}