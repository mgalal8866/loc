
@push('styles')
<style>
    .profile-user-img:hover {
        background-color: blue;
        cursor: pointer;
    }
</style>
@endpush
<div>
    @section('title')
    Branch
    @stop
    @section('page')
    Branch
    @endsection
    @section('page2')
    Branch
    @endsection
    <div wire:ignore.self class="modal fade" id="alertModal" tabindex="-1" role="dialog" aria-labelledby="alertModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="alertModalLabel">Confirm</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                         <span aria-hidden="true close-btn">×</span>
                    </button>
                </div>
               <div class="modal-body">

                    <p> لم يتم تحديد اى منطقة هل تريد اضافة المتجر لجميع المناطق</p>
                </div>
                <div class="modal-footer">
                   <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">Close</button>
                   <button type="button" wire:click.prevent="SaveStoreToAllRegion()" class="btn btn-danger close-modal" data-dismiss="modal">{{__('OK')}}</button>


                </div>
            </div>
        </div>
    </div>
    <div  wire:loading  wire:target='SaveStoreToAllRegion'>
        <x-load></x-load>
    </div>
    <form wire:submit.prevent='checkstore()'   enctype="multipart/form-data">
        <div class="row" >
            <div class="col-md-12">
                <div class="card card-secondary">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('maindetails')}}</h3>
                        <div class="card-tools">
                             <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">

                        <div class="row" >
                            <div class="col-md-8" >
                                <div class="row" >
                                    <div class="col-md-6" >
                                        <div class="form-group">
                                            <label for="inputName">{{ __('minestore')}}</label>
                                            <input type="text" id="inputName" wire:model.lazy='name' class="form-control @error('name') is-invalid @enderror"  required>
                                            @error('name')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3" >
                                        <div  class="form-group">
                                            <label for="selectactivestore">{{ __('active') }}</label>
                                            <select id="selectactivestore"  wire:model.lazy='activestore' class="form-control pt-1   @error('activestore') is-invalid @enderror" >
                                                <option value="">Select active</option>
                                                <option value="0">{{ __('active') }}</option>
                                                <option value="1">{{ __('unactive') }}</option>
                                            </select>
                                            @error('activestore')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror

                                        </div>

                                    </div>
                                    <div class="col-md-3" >
                                        <div class="form-group">
                                            <label for="inputnumberbranch">{{ __('numberbranch')}}</label>
                                            <input type="text" id="inputnumberbranch" wire:model.lazy='numberbranch' class="form-control @error('numberbranch') is-invalid @enderror" >
                                            @error('numberbranch')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror

                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6" >
                                        <div   class="form-group">
                                            <label for="selectcategory">{{ __('category') }}</label>
                                            <select id="selectcategory"  wire:model='selectcategory' class="form-control pt-1   @error('selectcategory') is-invalid @enderror" required>
                                                <option value="">Select category</option>
                                                @foreach ( $categorys as $category )
                                                    @if(!$category->parent_id)
                                                      <option value="{{$category->id}}" >{{$category->name}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        @error('selectcategory')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6" >
                                        @empty(!$subcategorys)
                                            <div   class="form-group">
                                                <label for="selectsubcategory">{{ __('subcategory') }}</label>
                                                <select id="selectsubcategory"  wire:model='selectsubcategory' class="form-control pt-1   @error('selectsubcategory') is-invalid @enderror"  required>
                                                    <option value="">Select Sub category</option>
                                                        @foreach ( $subcategorys as $subcategory )
                                                            <option value="{{$subcategory->id}}" >{{$subcategory->name}}</option>
                                                        @endforeach
                                                </select>
                                            @error('selectsubcategory')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                            </div>
                                        @endempty
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    {{-- <div class="card-footer bg-secondary">
                        <button type="submit" class="btn btn-primary" > Save </button>
                    </div> --}}
                </div>
            </div>
        </div>
        <div class="card card-primary card-outline card-outline-tabs">
            <div wire:ignore.self class="card-header p-0 border-bottom-0">

                    <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">

                        @foreach( $branchlist as $index => $branch)
                            <li class="nav-item">
                                    <a class="nav-link {{ $index == 0 ? 'active' :'' }}" id="b{{ $index }}-tab"
                                        data-toggle="pill" href="#b{{ $index }}" role="tab"
                                        aria-controls="b{{ $index }}" aria-selected="true" >
                                      @if($index != 0)
                                        <span class='badge bg-danger' wire:click.prevent='removebranch({{ $index }})'>X</span>
                                       @endif
                                      Branch  {{ $index+1 }}
                                </a>

                            </li>
                        @endforeach
                        {{-- <li class="nav-item">
                            <a wire:click.prevent="addbranch()" class="nav-link" ><i class="fas fa-plus"></i></a>
                        </li> --}}
                    </ul>

            </div>
            <div class="card-body">
                <div class="tab-content" id="custom-tabs-four-tabContent">
                    @foreach( $branchlist as $index => $branch)
                        <div  wire:ignore.self class="tab-pane fade   {{ $index == 0 ? ' show active' :'' }}"
                                id="b{{ $index }}"
                                role="tabpanel"
                                aria-labelledby="b{{ $index }}-tab"
                                >
                            <div class="card">
                                    <div class="card-body">

                                        <div class="row">
                                            <div class="col-md-8" >
                                                <div class="form-group">
                                                    <label for="inputDescription{{ $index }}">{{ __('description')}}</label>
                                                    <textarea id="inputDescription{{ $index }}" wire:model.defer='branchlist.{{ $index }}.descriptionbranch'    rows="5" class="form-control @error('branchlist.descriptionbranch') is-invalid @enderror" required></textarea>
                                                    @error('branchlist.descriptionbranch')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4" >

                                                <label for="inputName">{{ __('image')}}</label>
                                                <div class="card card-secondary card-outline" >
                                                    <div class="card-body box-profile">
                                                        <div class="row">
                                                            <div class="col-md-12 text-center">
                                                                <div class="row  text-center">
                                                                <div class="col-lg-8 col-md-8  text-center">
                                                                    @if ($branchlist[$index]['importimage'])
                                                                    <img src="{{ $branchlist[$index]['importimage']->temporaryUrl() }}"  class="img-thumbnail" alt="">
                                                                    @else
                                                                    <img src="{{ $branchlist[$index]['image'] }}" alt=""class="img-thumbnail">
                                                                    @endif
                                                                </div>
                                                            </div>
                                                                <div x-data="{ isUploading: false, progress: 5 }" x-on:livewire-upload-start="isUploading = true" x-on:livewire-upload-finish="isUploading = false; progress = 5" x-on:livewire-upload-error="isUploading = false" x-on:livewire-upload-progress="progress = $event.detail.progress">
                                                                        <a class="btn btn-success btn-sm btn-file-upload pt-2">
                                                                            اختر صورة <input id="importimage{{ $index }}" type="file" name="file" size="40"
                                                                                accept=".png, .jpg, .jpeg, .gif" wire:model='branchlist.{{ $index }}.importimage' required>
                                                                                {{-- branchlist.{{ $index }}.importimage --}}
                                                                        </a>
                                                                    <div  x-show.transition="isUploading" class="progress progress-sm mt-2 rounded">
                                                                        <div class="progress-bar bg-success  progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" x-bind:style="`width: ${progress}%`">
                                                                            <span class="sr-only">40% Complete (success)</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            {{-- <div class="col-md-12 text-center">
                                                                    <p class="text-center text-danger"><small> * بعد اختيار الصورة يتم حفظها</small></p>
                                                            </div> --}}
                                                        </div>
                                                    </div>
                                                </div>


                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4" >
                                                <div    class="form-group">
                                                    <label for="selectactive{{ $index }}">{{ __('active') }}</label>
                                                    <select id="selectactive{{ $index }}"  wire:model.defer='branchlist.{{ $index }}.active' class="form-control pt-1   @error('branchlist.active') is-invalid @enderror" >
                                                        <option value="0">{{ __('active') }}</option>
                                                        <option value="1">{{ __('unactive') }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4" >
                                                <div  class="form-group">
                                                    <label for="selectapproval{{ $index }}">{{ __('approval') }}</label>
                                                    <select id="selectapproval{{ $index }}"  wire:model.defer='branchlist.{{ $index }}.approval' class="form-control pt-1   @error('branchlist.approval') is-invalid @enderror" >
                                                        <option value="0">accept</option>
                                                        <option value="1">waiting</option>
                                                        <option value="2">unacceptable</option>
                                                    </select>
                                                    </div>
                                            </div>
                                            <div class="col-md-2" >

                                                <div class="form-group">
                                                    <label for="selectactive{{ $index }}">{{ __('show_top') }}</label>
                                                    <select id="selecttop{{ $index }}"  wire:model.defer='branchlist.{{ $index }}.top' class="form-control pt-1   @error('branchlist.top') is-invalid @enderror" >
                                                        <option value="1">{{ __('Star') }}</option>
                                                        <option value="0">{{ __('Normal') }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-2" >
                                                <div class="form-group">
                                                    <label for="numproduct{{ $index }}">{{ __('numproduct')  }}</label>
                                                    <input type="text" id="numproduct{{ $index }}" wire:model.defer='branchlist.{{ $index }}.numproduct' class="form-control @error('branchlist.numproduct') is-invalid @enderror" >
                                                    @error('branchlist.numproduct')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" >
                                            <div class="col-md-6" >
                                                <div class="form-group">
                                                    <label for="opentime{{ $index }}">{{__('opentime')}}</label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-clock"></i></span>
                                                        </div>
                                                        <x-timepicker wire:model.defer="branchlist.{{ $index }}.opentime" id="opentime{{ $index }}" :error="'branchlist.{{ $index }}.opentime'" />
                                                        @error('branchlist.opentime')
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6" >
                                                <div class="form-group">
                                                    <label for="closetime{{ $index }}">{{ __('closetime') }}</label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-clock"></i></span>
                                                        </div>
                                                        <x-timepicker wire:model.defer="branchlist.{{ $index }}.closetime" id="closetime{{ $index }}" error="branchlist.{{ $index }}.closetime" />
                                                        @error('branchlist.closetime')
                                                            <div class="invalid-feedback">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" >
                                            <div class="col-md-6" >
                                                <div  class="form-group">
                                                    <label for="start_date{{ $index }}">{{__('start_date')}}</label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                                        </div>
                                                        <x-datepicker wire:model.dafer="branchlist.{{ $index }}.start_date" id="start_date{{ $index }}" :error="'branchlist.{{ $index }}.start_date'" />
                                                        @error('branchlist.start_date')
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6" >
                                                <div class="form-group">
                                                    <label for="expiry_date{{ $index }}">{{__('expiry_date')}}</label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                                        </div>
                                                        <x-datepicker wire:model.dafer="branchlist.{{ $index }}.expiry_date" id="expiry_date{{ $index }}" :error="'branchlist.{{ $index }}.expiry_date'" />
                                                        @error('branchlist.expiry_date')
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-8" >

                                                <div class="form-group">
                                                    <label for="address{{ $index }}">{{ __('address')  }}</label>
                                                    <input type="text" id="address{{ $index }}" wire:model.defer='branchlist.{{ $index }}.address' class="form-control @error('branchlist.address') is-invalid @enderror" >
                                                    @error('branchlist.address')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                                </div>
                                            </div>
                                            <div class="col-md-2" >
                                                <div  class="form-group">
                                                    <label for="selectcity{{ $index }}">{{ __('city') }}</label>
                                                    <select id="selectcity{{ $index }}"  wire:model='branchlist.{{ $index }}.city_id' class="form-control pt-1   @error('branchlist.city_id') is-invalid @enderror" >
                                                        <option value="" disabled>Select City</option>
                                                        @foreach ( $citys as $city )
                                                            <option value="{{$city->id}}"> {{$city->name}}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('branchlist.city_id')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                                </div>
                                            </div>

                                            <div class="col-md-2" >
                                                <div  class="form-group">
                                                    <label for="selectregion{{ $index }}">{{ __('region') }}</label>
                                                    <select  id="selectregion{{ $index }}" wire:model='branchlist.{{ $index }}.region_id' class="form-control pt-1 @error('branchlistregion_id') is-invalid @enderror" >
                                                        <option value="">Select Region</option>
                                                        @if (!empty($regions))

                                                            @foreach ( $regions[$loop->index] as $reg )
                                                                <option value="{{$reg['id']}}" >{{$reg['region_name_ar']}}</option>
                                                            @endforeach
                                                            {{-- @foreach ( $regions as $reg )
                                                                <option value="{{$reg->id}}" >{{$reg->name}}</option>
                                                            @endforeach --}}
                                                        @endif
                                                    </select>
                                                    @error('branchlist.region_id')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror

                                                </div>

                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6" >
                                                <div class="form-group">
                                                    <label for="phone{{ $index }}">{{ __('phone')  }}</label>
                                                    <input type="text" id="phone{{ $index }}" wire:model.defer='branchlist.{{ $index }}.phone' class="form-control @error('branchlist.phone') is-invalid @enderror" >
                                                    @error('branchlist.phone')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6" >
                                                <div class="form-group">

                                                    <label for="phonetwo{{ $index }}">{{ __('phone2')  }}</label>
                                                    <input type="text" id="phonetwo{{ $index }}" wire:model.defer='branchlist.{{ $index }}.phonetwo' class="form-control @error('branchlist.phonetwo') is-invalid @enderror" >
                                                    @error('branchlist.phonetwo')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                                </div>

                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6" >
                                                <div class="form-group">
                                                    <label for="lat{{ $index }}">{{ __('lat')  }}</label>
                                                    <input type="text" id="lat{{ $index }}" wire:model.defer='branchlist.{{ $index }}.lat' class="form-control @error('branchlist.lat') is-invalid @enderror" >
                                                    @error('branchlist.lat')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6" >
                                                <div class="form-group">
                                                    <label for="lng{{ $index }}">{{ __('lng')  }}</label>
                                                    <input type="text" id="lng{{ $index }}" wire:model.defer='branchlist.{{ $index }}.lng' class="form-control @error('branchlist.lng') is-invalid @enderror" >
                                                    @error('branchlist.lng')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                                </div>

                                            </div>
                                        </div>
                                        <div class="mapouter">
                                            <div class="gmap_canvas">
                                                {{-- <iframe   width="100%"  height="500" id="gmap_canvas" src="https://maps.google.com/maps?q={{$branchlist[]['lat']}},{{ $branchlist[]['lng']  }}&t=&z=13&ie=UTF8&iwloc=&output=embed" frameborder="1" scrolling="no" marginheight="0" marginwidth="0"></iframe>
                                                <br><style>.mapouter{position:relative;text-align:right;height:500px; width:100%;}</style><a href="https://www.embedgooglemap.net">embed a google map in html</a>
                                                    <style>
                                                        .gmap_canvas {overflow:hidden;background:none!important;height:500px;  width:100%;}
                                                    </style> --}}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-secondary">
                                        <button class="btn btn-success" type="submit"> Save </button>
                                    </div>
                                </div>

                        </div>
                        {{-- <div class="alert alert-warning">
                                <strong> No Branch!</strong>
                        </div> --}}

                    @endforeach
                </div>
            </div>

        </div>
    </form>
</div>



@push('jslive')

<script>
     window.addEventListener('openalertModal', event => {
            $("#alertModal").modal('show');
        });
        window.addEventListener('closealertModal', event => {
            $("#alertModal").modal('hide');
        });

 var hash = document.location.hash;
 if (hash) {
 $('.nav-tabs a[href="'+hash+'"]').tab('show');
 }

 // Change hash for page-reload
 $('.nav a').on('shown.bs.tab', function (e) {
 window.location.hash = e.target.hash;
 });
 </script>
@endpush
    {{-- @push('alpine-plugins')
    <!-- Alpine Plugins -->
    <script defer src="https://unpkg.com/@alpinejs/persist@3.x.x/dist/cdn.min.js"></script>
    @endpush --}}
