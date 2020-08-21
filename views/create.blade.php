@extends('layouts.app')

@section('content')
<section id="page-title">
      <div class="row">
          <div class="page-title-left col-2 col-sm-3 col-lg-3">
              <div class="page-title-inner"> </div>
            </div>

            <div class="page-title-right col-10 col-sm-9 col-lg-9">
                <div class="breadcrumbs">
                  <h3><a href="{{route('listing.create')}}">Advert Details</a></h3>
                <p><a href="{{url('/')}}">Home </a><span>.</span><a href="{{route('listing.create')}}" class="active"> Advert Details</a></p>
              <div>
              </div>
          </div>
</section>
<section class="accordien_section">
<form action="{{ route('listing.post.create')}}" id="listingForm" class="listing-create" method="post">
@csrf
      <div class="container">
        <div class="row post_free">
          <div class="col-md-12">
            @if (session()->has('success_message'))
            <div class="alert alert-success">
                {{ session()->get('success_message') }}
            </div>
            @endif @if(count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
          </div>

          <div class="col-md-12">

                  <div class="card card-default">
                    <div class="card-header">
                        <h4 class="card-title">
                        <p>Vehicle Advert</p>
                        </h4>
                    </div>
                    <div id="collapse1" class="collapse show">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="add_typ">
                                            <label for="form_name">Listing Type</label>
                                            <input type="radio" id="private" name="listing_type" value="private"  {{ old('listing_type') == 'private' ? 'checked':'' }}>
                                            <label for="private">Private</label>
                                            <input type="radio" id="trade" name="listing_type" value="trade" {{ old('listing_type') == 'private' ? 'checked':'' }}>
                                            <label for="trade">Trade</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input type="text" name="listing_title" placeholder="Listing title" value="{{ old('listing_title')}}" required/>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input type="number" name="listing_price" placeholder="Price" value="{{ old('listing_price')}}" required/>
                                        <div class="checkbox add_typ">
                                            <div class="form-check">
                                                <input class="" type="checkbox" name="negotiable" value="y" id="remember" {{ old('negotiable') == 'y' ? 'checked': '' }}>
                                                <label class="form-check-label" for="remember"> Negotiable </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                            <label class="control-label">Description</label>
                               <textarea id="exampleTextarea" rows="3" name="listing_description" placeholder="" required>{{ old('listing_description')}}</textarea>
                                </div>

                            </div>
                            <div class="descpt">
                                <p>Picture</p>
                                  <div class="uploader-wrapper">
                                     <p>Add up to 10 images. (Max size: 8MB per image).</p>
                                    <div class="row">
                                        <input type="hidden" value="listings" id="filepond-type">
                                            <div class="uploader-block col"><input type="file" class="my-pond" multiple name="filepond[]"/></div>
                                            <div class="uploader-block col"><input type="file" class="my-pond" multiple name="filepond[]" /></div>
                                            <div class="uploader-block col"><input type="file" class="my-pond" multiple name="filepond[]"/></div>
                                            <div class="uploader-block col"><input type="file" class="my-pond" multiple name="filepond[]"/></div>
                                            <div class="uploader-block col"><input type="file" class="my-pond" multiple name="filepond[]"/></div>

                                   </div>
                                   <div class="row">
                                           <div class="uploader-block col"><input type="file" class="my-pond" multiple name="filepond[]"/></div>
                                           <div class="uploader-block col"><input type="file" class="my-pond" multiple name="filepond[]"/></div>
                                           <div class="uploader-block col"><input type="file" class="my-pond" multiple name="filepond[]"/></div>
                                           <div class="uploader-block col"><input type="file" class="my-pond" multiple name="filepond[]"/></div>
                                           <div class="uploader-block col"><input type="file" class="my-pond" multiple name="filepond[]"/></div>

                                  </div>
                                   </div>
                            </div>
                        </div>
                    </div>
                </div>
              <div class="card card-default bottom_card">
                    <div class="card-header">
                        <h4 class="card-title">
                        <p>Seller information</p>
                        </h4>
                    </div>
                    <div id="collapse1" class="collapse show">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="form-group">
                                        <input type="text" name="seller_first_name" placeholder="First Name" value="{{old('seller_first_name')}}" required />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input type="text" name="seller_last_name" value="{{ old('seller_last_name')}}" placeholder="Last Name" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input type="text" name="seller_email" value="{{ old('seller_email') }}" placeholder="Email address" required/>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="form-group">
                                        <input type="text" name="seller_phone" placeholder="Phone Number" />
                                        <div class="form-group">
                                                <div class="form-check">
                                                    <input class="" type="checkbox" value="y" name="hide_phone" id="hide_phone">
                                                    <label class="form-check-label" for="hide_phone">
                                                    Hide the phone number on this ad.
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="slct-group">
                                        <select name="seller_location" required>
                                            <option value="" {{ !old('seller_location') ? 'selected' : '' }}> Select Location</option>
                                            @forelse($countries as $country)
                                            <option value="{{ $country->id }}" {{ old('seller_location')  == $country->id ? 'selected' : '' }} >{{ $country->name}}</option>
                                            @empty
                                            @endforelse
                                        </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input type="text" name="city" value="{{old('city')}}" placeholder="City" required/>
                                        </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="form-group">
                                        <input type="text" name="postcode" placeholder="postcode" value="{{old('postcode')}}" pattern=".{6,7}" title="Postcode should be 6 to 7 Character without Space" value="" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="card card-default bottom_card">
                    <div class="card-header">
                        <h4 class="card-title">
                        <p>Vehicle information</p>
                        </h4>
                    </div>
                    <div id="collapse1" class="collapse show">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <select name="vehicle_make" id="listing-make" class="form-control" required>
                                            <option value="">Select Make</option>
                                            @forelse($makes as $make)
                                            <option value="{{ $make->id}}">{{ $make->name }}</option>
                                            @empty
                                            <option value="">No make available</option>
                                            @endforelse
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <select name="vehicle_model" id="listing-model" class="form-control" required>
                                            <option value="">Select Model</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                    <div class="slct-group">
                                        <select name="vehicle_fuel" required>
                                            <option value="" {{ !old('vehicle_fuel') ? 'selected' : '' }}>Select Fuel Type</option>
                                            <option value="diesel" {{ old('vehicle_fuel')  == "diesel" ? 'selected' : '' }}>Diesel</option>
                                            <option value="petrol" {{ old('vehicle_fuel')  == "petrol" ? 'selected' : '' }}>Petrol</option>
                                            <option value="electric" {{ old('vehicle_fuel')  == "electric" ? 'selected' : '' }}>Electric</option>
                                            <option value="lpg" {{ old('vehicle_fuel')  == "lpg" ? 'selected' : '' }}>LPG</option>
                                            <option value="other" {{ old('vehicle_fuel')  == "other" ? 'selected' : '' }}>Other</option>
                                        </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="slct-group">
                                        <select class="form-control" name="vehicle_gearbox" id="modal-search-gearbox" required>
                                            <option value="">Select Gearbox</option>
                                            <option value="automatic" {{ old('vehicle_gearbox')  == "automatic" ? 'selected' : '' }}>Automattic</option>
                                            <option value="manual" {{ old('vehicle_gearbox')  == "manual" ? 'selected' : '' }}>Manual</option>
                                        </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="slct-group">
                                        <select name="vehicle_condition" required>
                                        <option value="">Select Condition</option>
                                            <option value="used" {{ old('vehicle_condition')  == "used" ? 'selected' : '' }}>Used</option>
                                            <option value="new" {{ old('vehicle_condition')  == "new" ? 'selected' : '' }}>New</option>
                                        </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="slct-group">
                                        <select name="vehicle_body" required>
                                            <option value="">Select Car Type</option>
                                            <option value="na" {{ old('vehicle_body')  == "na" ? 'selected' : '' }}>N/A</option>
                                            <option value="Station Wagon" {{ old('vehicle_body')  == "Station Wagon" ? 'selected' : '' }}>Station Wagon</option>
                                            <option value="Hard Top" {{ old('vehicle_body')  == "Hard Top" ? 'selected' : '' }}>Hard Top</option>
                                            <option value="Commercial" {{ old('vehicle_body')  == "Commercial" ? 'selected' : '' }}>Commercial</option>
                                        </select>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input type="text" name="vehicle_engine_size" value="{{old('vehicle_engine_size')}}" placeholder="Engine" required/>
                                        <p class="great_title">Engine Power in (hp)</p>
                                        </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

              <div class="card card_make bottom_card">
                    <div class="card-header">
                        <h4 class="card-title">
                        <p>Make your Listing Premium</p>
                        </h4>
                    </div>
                    <div id="collapse1" class="collapse show">
                        <div class="card-body">

                            <ul class="list_prime" id="listing-variation">

                            @forelse($listingVariations as $variation)
                                <li class="radio">
                                    <div class="form-group">
                                        <input type="radio" id="variation-{{$variation->id}}" name="listing_variation" value="{{$variation->id}}" data-price="{{$variation->price}}">
                                        <label for="variation-{{$variation->id}}">{{$variation->name}}</label>
                                        <h6>@money($variation->price)</h6>
                                    </div>
                                </li>
                            @empty
                            @endforelse
                            </ul>
                        </div>
                        <div class="pay_ment">
                            <div class="row">
                            <div class="col-md-6">

                                <div class="radio">

                                        <div class="method_pay">

                                        </div>



                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="amount_div"><p>Payable Amount : £<span class="money-price">00.00</span></p></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

              <div class="remember_info">


                    <div class="checkbox">
                        <div class="form-group">
                            <div class="form-check">
                                <input class="" type="checkbox" name="remember_contact" id="remember_contact" value="yes">
                                <label class="form-check-label" for="remember_contact">
                                    Remember above contact information
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="" type="checkbox" name="Web_Accept" id="web_accept" value="yes" required>
                                <label class="form-check-label" for="web_accept">
                                    Website terms & conditions accepted
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="" type="checkbox" name="remember_me" id="relation_listing" value="yes" required>
                                <label class="form-check-label" for="relation_listing">
                                    I agree to being contacted by LR Trader (LR Trader needs to contact you in relation to your listing enquires & account notifications).
                                </label>
                            </div>
                        </div>
                    </div>
                  <div id="lr_recaptcha"></div>
                  <button type="submit" id="recaptcha_button" class="btn btn-default">Submit</button>
              </div>
            </div>
          </div>
    </div>
    </form>
    </section>
@endsection

@section('footer_scripts')

<!-- include FilePond library -->
<script src="{{ asset('js/filepond.min.js') }}"></script>

<!-- include FilePond plugins -->
<script src="{{ asset('js/filepond-plugin-image-preview.min.js') }}"></script>
<script src="{{ asset('js/filepond-plugin-file-validate-type.min.js') }}"></script>
<script src="{{ asset('js/jquery.validate.min.js') }}"></script>

<!-- include FilePond jQuery adapter -->
<script src="{{ asset('js/filepond.jquery.js') }}"></script>
<script src="{{ route('filepond.scripts.js') }}"></script>

@endsection

@section('css')
<link href="{{ asset('css/filepond.css') }}" rel="stylesheet">
@endsection
