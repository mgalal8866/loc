<div>

    <style>
        .li-mt {
            display: block !important;
        }
        .options-dropdown {
        min-width: 180px;
        /* left: 50% !important; */
        right: auto !important;
        text-align: left;
        /* transform: translate(-50%, 0); */
        }

        .dropdown-menu {
        position: absolute;
        top: 100%;
        left: 0;
        z-index: 1000;
        display: none;
        float: left;
        min-width: 160px;
        padding: 5px 0;
        margin: 2px 0 0;
        font-size: 14px;
        text-align: left;
        list-style: none;
        background-color: #fff;
        -webkit-background-clip: padding-box;
        background-clip: padding-box;
        border: 1px solid #ccc;
        border: 1px solid rgba(0,0,0,.15);
            border-top-color: rgba(0, 0, 0, 0.15);
            border-right-color: rgba(0, 0, 0, 0.15);
            border-bottom-color: rgba(0, 0, 0, 0.15);
            border-left-color: rgba(0, 0, 0, 0.15);
        border-radius: 4px;
        -webkit-box-shadow: 0 6px 12px rgba(0,0,0,.175);
        box-shadow: 0 6px 12px rgba(0,0,0,.175);
        }

        .btn-select-option {
            font-size: 12px;
            margin: 0;
            padding: 3px 6px;
            }

        /* .dropdown-menu > li > a {
            display: block;
            padding: 3px 20px;
            clear: both;
            font-weight: 400;
            line-height: 1.42857143;
            color: #333;
            white-space: nowrap;
            }
            .options-dropdown li a {
                font-weight: 600;
                padding: 8px;
                color: #555;
            } */


            .dropdown-menu > li > a {
                /* text-align: right !important; */
                display: block;
                padding: 3px 20px;
                clear: both;
                font-weight: 400;
                line-height: 1.42857143;
                color: #777;
                white-space: nowrap;
            }
            button, a {
                outline: none !important;
            }
    </style>
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card card-primary">
                <!-- /.card-header -->
                <div class="card-header with-border">
                    <h3 class="card-title">?????????? ???????? ???????? ??????????????</h3>
                </div><!-- /.card-header -->

                <!-- form start -->
                <form wire:submit.prevent='add' enctype="multipart/form-data"accept-charset="utf-8">

                    <div class="card-body">
                        <div class="form-group">
                            <label class="control-label">???????? (1920x1080)
                                {{-- @if ($image)
                                {{ $image->getClientOriginalName() }}
                                @else
                                Choose Image
                                @endif --}}
                            </label>
                            <div x-data="{ isUploading: false, progress: 5 }" x-on:livewire-upload-start="isUploading = true" x-on:livewire-upload-finish="isUploading = false; progress = 5" x-on:livewire-upload-error="isUploading = false" x-on:livewire-upload-progress="progress = $event.detail.progress">
                            <div class="display-block">
                                <a class="btn btn-success btn-sm btn-file-upload">
                                    ???????? ???????? <input type="file" name="file" size="40"
                                        accept=".png, .jpg, .jpeg, .gif" wire:model='image' required>
                                </a>
                            </div>
                            <div x-show.transition="isUploading" class="progress progress-sm mt-2 rounded">
                                <div class="progress-bar bg-success  progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" x-bind:style="`width: ${progress}%`">
                                    <span class="sr-only">40% Complete (success)</span>
                                </div>
                            </div>
                            @if ($image)
                            <img src="{{ $image->temporaryUrl() }}" class="img d-block mt-2 w-100 rounded">
                            @else
                            {{-- <img src="{{ $state['avatar_url'] ?? '' }}" class="img d-block mb-2 w-100 rounded"> --}}
                            @endif
                            {{-- <img src="data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw=="
                                id="img_preview_file" class="img-file-upload-preview"> --}}
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <button  wire:loading.attr="disabled" type="submit" class="btn btn-primary pull-right">{{ __('add') }} </button>
                    </div>
                    <!-- /.card-footer -->
                </form><!-- form end -->
            </div>
        </div>

        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header with-border">
                    <h3 class="card-title">?????????? ????????????????</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="table-responsive">
                                <div id="DataTables_Table_0_wrapper"
                                    class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                                    <table
                                        class="table table-bordered table-striped cs_datatable_lang dataTable no-footer"
                                        role="grid" aria-describedby="DataTables_Table_0_info"
                                        id="DataTables_Table_0">
                                            <thead>
                                                <tr role="row">
                                                    <th style="width: 38px;" width="20">??????????</th>
                                                    <th style="width: 258px;">????????</th>
                                                    <th style="width: 76.3px;">{{ __('tran.action') }}</th>
                                                </tr>
                                            </thead>
                                    <tbody>

                                     @forelse ($slider as $slide )
                                       <tr role="row">
                                                <td class="sorting_1">{{ $slide->id }}</td>
                                                <td><img src="{{ $slide->image }}"alt="" style="width: 200px;"></td>
                                            <td>
                                                <div class="dropdown">
                                                    <button
                                                        class="btn bg-purple dropdown-toggle btn-select-option"
                                                        type="button" data-toggle="dropdown">?????? ?????????????? <span
                                                            class="caret"></span>
                                                    </button>
                                                    <ul class="dropdown-menu options-dropdown">
                                                        <li>
                                                            <a href="#"><i class="fa fa-edit option-icon"></i>??????????</a>
                                                        </li>
                                                        <li>
                                                            <a href="#" wire:click.prevent='deleteslider({{$slide->id }})' ><i class="fa fa-trash option-icon"></i>??????</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                            <td class="sorting_1">No data</td>
                                    @endforelse
                                        </tbody>
                                    </table>

                            </div>
                        </div>
                    </div>
                </div><!-- /.card-body -->
            </div>
        </div>
    </div>


</div>
