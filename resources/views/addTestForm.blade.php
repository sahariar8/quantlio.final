<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modelHeading">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="testForm" name="testForm" class="form-horizontal" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="edit_id" name="edit_id">
                    <div class="form-group mb-3">
                        <label for="inputTestName">{{ __('Test Name') }}</label>
                        <input type="text" class="form-control" id="inputTestName" name="test_name" required
                            minlength="2" value="{{ old('test_name') }}">
                        <span id="spanTestName" class="spanErrorText" style="color:red;"></span>
                        @if ($errors->has('test_name'))
                        <span class="text-danger">{{ $errors->first('test_name') }}</span>
                        @endif
                    </div>

                    <div class="form-group mb-3">
                        <label for="inputclass">Class</label>
                        <input type="text" class="form-control" id="inputclass" name="class" required minlength="2"
                            value="{{ old('class') }}">
                        <span id="spanClassName" class="spanErrorText" style="color:red;"></span>
                        @if ($errors->has('class'))
                        <span class="text-danger">{{ $errors->first('class') }}</span>
                        @endif
                    </div>

                    <div class="form-group mb-3">
                        <label for="inputDescription">{{ __('Description') }}</label>
                        <input type="text" class="form-control" id="inputDescription" name="description"
                            value="{{ old('description') }}">
                    </div>

                    <div class="form-group mb-3">
                        <label for="inputCutoff">{{ __('Parent') }}</label>
                        <input type="text" class="form-control" id="inputCutoff" name="parent" required
                            value="{{ old('parent') }}">
                        <span id="spanCutoff" class="spanErrorText" style="color:red;"></span>
                        @if ($errors->has('parent'))
                        <span class="text-danger">{{ $errors->first('parent') }}</span>
                        @endif
                    </div>
                    <div class="form-group mb-3">
                        <label for="inputRange">{{ __('Metabolite') }}</label>
                        <input type="text" class="form-control" id="inputRange" name="metabolite" required
                            value="{{ old('metabolite') }}">
                        <span id="spanTestRange" class="spanErrorText" style="color:red;"></span>
                        @if ($errors->has('metabolite'))
                        <span class="text-danger">{{ $errors->first('metabolite') }}</span>
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="submit_form">{{ __('Save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>