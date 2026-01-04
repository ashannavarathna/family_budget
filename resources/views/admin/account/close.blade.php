@extends('layouts.admin') {{-- NOTE: AdminLTEの基本レイアウトを継承します --}}

@section('title', '決算期')

@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>決算期</h1>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="row justify-content-center">
                    <div class="col-md-8">

                        <div class="card card-info">
                            <div class="card-header text-center">
                                <h4 class="card-title mb-0">
                                    Account Period Closing
                                </h4>
                            </div>

                            <div class="card-body text-center">

                                <p class="text-muted mb-2">
                                    
                                    〜
                                    
                                </p>

                                <hr>

                                <div class="row mb-3">
                                    <div class="col-4">
                                        <div class="text-muted">Opening</div>
                                        <strong>
                                            ¥
                                        </strong>
                                    </div>

                                    <div class="col-4">
                                        <div class="text-muted">Income</div>
                                        <strong class="text-success">
                                            ¥
                                        </strong>
                                    </div>

                                    <div class="col-4">
                                        <div class="text-muted">Expense</div>
                                        <strong class="text-danger">
                                            ¥
                                        </strong>
                                    </div>
                                </div>

                                <hr>

                                <h3 class="">
                                    Closing Balance
                                    <br>
                                    ¥
                                </h3>

                                <hr>

                                <form method="POST" action="">
                                    @csrf
                                    <button
                                        type="submit"
                                        class="btn btn-success btn-lg"
                                        onclick="return confirm('Are you sure you want to close this account period?')">
                                        Close Account Period
                                    </button>
                                </form>

                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>

    </div>
</section>
@endsection