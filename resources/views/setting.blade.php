@extends('layouts.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    {{-- @include('shared.breadcrumb', ['pageHeading' => 'RxCUI']) --}}


    <!-- /.content-header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ 'Failed PDF Report ' }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">{{ __('Home') }}</a></li>
                        <li class="breadcrumb-item active">{{ 'Setting' }}</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    @include('addTradeForm')

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-12">

                    @if (session('save_success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('save_success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif

                    @if (session('save_error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('save_error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif

                    @if (session('validation_error'))
                    <input type="hidden" id="validation_error" value="{{ session('validation_error') }}" />
                    @endif

                    <div class="card">
                        <div class="card-header">
                            @can('insert-trades')
                            <button type="button" class="btn btn-primary" id="addTrades" data-toggle="modal"
                                data-target="#exampleModal" style="float:right;">
                                {{ __('Add Trade') }}
                            </button>
                            @endcan

                            <!-- Button trigger modal -->

                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped" data-click-to-select="true">
                                <thead>
                                    <tr>
                                        <th>{{ __('ID') }}</th>
                                        <th>{{ __('Key_Name') }}</th>
                                        <th>{{ __('Value') }} </th>
                                        <th>{{ __('Lab_Id') }} </th>
                                        <th>{{ __('Action') }} </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($settings as $trade)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $trade->keyname }}</td>
                                        <td>{{ $trade->value }}</td>
                                        <td>{{ $trade->lab_id }}</td>
                                        <td>
                                            <ul class="list-inline m-0">
                                                {{-- <li class="list-inline-item">
                                                        @can('edit-testDetails')
                                                            <button class="btn btn-success btn-sm rounded-0 edit" type="button" data-toggle="tooltip" data-placement="top" title="Edit" value="{{$test->id}}"><i
                                                    class="fa fa-edit"></i></button>
                                                @endcan
                                                </li> --}}
                                                <li class="list-inline-item">
                                                    @can('delete.failed-pdf')
                                                    <button class="btn btn-danger deletebtn btn-sm rounded-0"
                                                        type="button" data-toggle="tooltip" data-placement="top"
                                                        value="{{$trade->id}}" title="Delete"><i
                                                            class="fa fa-trash"></i></button>
                                                    @endcan
                                                </li>
                                            </ul>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>

                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ __('Delete Failed PDF Report') }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('delete.failed-pdf') }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <input type="hidden" id="deleting_id" name="delete_test_id">
                    <p style="padding-left: 20px;font-size: 18px;">{{ __('Are you sure want to delete') }} !</p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">{{ __('Yes') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

{{-- @section('load_external_js')
    <script>
        $('.deletebtn').on('click', function() {
            let modal = $('#deleteModal');
            modal.find('[name=deleting_id]').val($(this).val());
            modal.modal('show');
        })
    </script>
@endsection --}}

@section('load_external_js')
<script src="{{'js/failed_pdf.js'}}"></script>
@endsection