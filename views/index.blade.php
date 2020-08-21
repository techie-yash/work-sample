@extends('layouts.app')

@section('content')
<section id="page-title">
        <div class="row">
              <div class="page-title-left col-2 col-sm-3 col-lg-3">
                <div class="page-title-inner"> </div>
              </div>
              <div class="page-title-right col-10 col-sm-9 col-lg-9 scroll" data-aos="fade-left">
                  <div class="breadcrumbs">
                  <h3><a href="{{route('listing.index')}}">Listings</a></h3>
                    <p><a href="{{route('index')}}">Home </a> <span>.</span><a href="{{route('listing.index')}}" class="active">  Vehicle Listings</a>  </p>
                    <div>
                    </div>
                  </div>
                </div>
          </div>
    </section>

<section id="products-select">
       <div class="container">
         <div class="selection-wrap">
          <form method="get" action="{{ route('listing.search')}}">
          <div class="row">
            <div class="col-sm-12 col-lg-2 product-banner">
            @include('partials.advertisement', ['slug' => 'vehicle-search-left'])
            </div>


          <div class="col-sm-12 col-lg-10 product">
            <div class="product-filter">
              <h3 class="filter-heading"> FILTER VEHICLES </h3>
              <div class="row row-top">
                <div class="form-group col-6 col-sm-3">
                  <select name="make" id="search-make" class="form-control">
                    <option value="">Select Make</option>
                    @forelse($makes as $make)
                    <option value="{{ $make->id}}" {{ (request('make') == $make->id ) ? 'selected' : ''}}>{{ $make->name }}</option>
                    @empty
                    <option value="">No make available</option>
                    @endforelse
                  </select>
                </div>
                <div class="form-group col-6 col-sm-3">

                    @if( request('model') )
                    @php
                     $model = \App\CarModel::where('id', request('model'))->first();
                    @endphp
                    <select name="model" id="search-model" class="form-control" >
                      <option value="{{ $model->id }}"> {{ $model->name }}</option>
                    </select>
                    @else
                    <select name="model" id="search-model" class="form-control">
                          <option value="">Select Model</option>
                    </select>
                    @endif
                </div>
                <div class="form-group col-6 col-sm-3">
                    <input type="text" class="form-control" placeholder="postcode" name="postcode" pattern=".{6,7}" title="Postcode should be 6 to 7 Character without Space" value="{{ request('postcode')}}">
                </div>
                <div class="form-group col-6 col-sm-3">
                    <select class="form-control" name="distance" id="search-distance">
                        <option value="">Distance (national)</option>
                        <option value="1" {{ (request('distance') == "1" ) ? 'selected' : ''}}>Within 1 mile</option>
                        <option value="5" {{ (request('distance') == "5" ) ? 'selected' : ''}}>Within 5 miles</option>
                        <option value="10" {{ (request('distance') == "10" ) ? 'selected' : ''}}>Within 10 miles</option>
                        <option value="15" {{ (request('distance') == "15" ) ? 'selected' : ''}}>Within 15 miles</option>
                        <option value="20" {{ (request('distance') == "20" ) ? 'selected' : ''}}>Within 20 miles</option>
                        <option value="25" {{ (request('distance') == "25" ) ? 'selected' : ''}}>Within 25 miles</option>
                        <option value="30" {{ (request('distance') == "30" ) ? 'selected' : ''}}>Within 30 miles</option>
                        <option value="35" {{ (request('distance') == "35" ) ? 'selected' : ''}}>Within 35 miles</option>
                        <option value="40" {{ (request('distance') == "40" ) ? 'selected' : ''}}>Within 40 miles</option>
                        <option value="45" {{ (request('distance') == "45" ) ? 'selected' : ''}}>Within 45 miles</option>
                        <option value="50" {{ (request('distance') == "50" ) ? 'selected' : ''}}>Within 50 miles</option>
                        <option value="55" {{ (request('distance') == "55" ) ? 'selected' : ''}}>Within 55 miles</option>
                        <option value="60" {{ (request('distance') == "60" ) ? 'selected' : ''}}>Within 60 miles</option>
                        <option value="70" {{ (request('distance') == "70" ) ? 'selected' : ''}}>Within 70 miles</option>
                        <option value="80" {{ (request('distance') == "80" ) ? 'selected' : ''}}>Within 80 miles</option>
                        <option value="90" {{ (request('distance') == "90" ) ? 'selected' : ''}}>Within 90 miles</option>
                        <option value="100" {{ (request('distance') == "100" ) ? 'selected' : ''}}>Within 100 miles</option>
                        <option value="200" {{ (request('distance') == "200" ) ? 'selected' : ''}}>Within 200 miles</option>
                    </select>
                  </div>
                </div>

                  <div class="row row-bottom">
                        <div class="form-group col-4 col-sm-3">
                                <select class="form-control" name="price" id="search-price">
                                    <option value="">Select Price</option>
                                    <option value="0" {{ (request('price') == "0" ) ? 'selected' : ''}}>£0 - £500</option>
                                    <option value="500" {{ (request('price') == "500" ) ? 'selected' : ''}}>£500 - £1000</option>
                                    <option value="1000" {{ (request('price') == "1000" ) ? 'selected' : ''}}>£1000 - £5000</option>
                                    <option value="5000" {{ (request('price') == "5000" ) ? 'selected' : ''}}>£5000 - £10000</option>
                                    <option value="10000" {{ (request('price') == "10000" ) ? 'selected' : ''}}>£10000 - £50000</option>
                                    <option value="50000" {{ (request('price') == "50000" ) ? 'selected' : ''}}>£50000 - £100000</option>
                                </select>
                              </div>

                      <div class="form-group col-4 col-sm-3">
                        <select class="form-control" name="fuel">
                          <option value="" >Select Fuel Type</option>
                          <option value="diesel" {{ (request('fuel') == "diesel" ) ? 'selected' : ''}}>Diesel</option>
                          <option value="petrol" {{ (request('fuel') == "petrol" ) ? 'selected' : ''}}>Petrol</option>
                          <option value="electric" {{ (request('fuel') == "electric" ) ? 'selected' : ''}}>Electric</option>
                          <option value="lpg" {{ (request('fuel') == "lpg" ) ? 'selected' : ''}}>LPG</option>
                          <option value="other" {{ (request('fuel') == "other" ) ? 'selected' : ''}}>Other</option>
                        </select>
                      </div>
                      <div class="form-group col-4 col-sm-3">
                        <select class="form-control" name="gearbox" >
                          <option value="">Select Gearbox</option>
                          <option value="automatic" {{ (request('gearbox') == "automatic" ) ? 'selected' : ''}}>Automattic</option>
                          <option value="manual" {{ (request('gearbox') == "manual" ) ? 'selected' : ''}}>Manual</option>
                        </select>
                      </div>

                      <div class="form-group col-sm-3">
                        <button type="submit" class="btn btn-default">search</button>
                      </div>
                  </div>

                </div>
            </form>
                  <div class="filter-bottom">
                    <div class="row">
                      <div class="col-sm-12 col-lg-3 sort-icons">
                        <button class="btn btn-list btn-sort" type="button" onclick="listView()"><i class="fa fa-bars"></i></button>
                        <button class="btn btn-grid btn-sort active" type="button" onclick="gridView()"><i class="fa fa-th-large"></i></button>
                      </div>
                      <div class="col-sm-12 col-lg-4 price-slider">
                      </div>

                      <div class="col-sm-6 col-lg-3 price-filter">
                        <div class="row">
                         <label for="price-min" class="price-min col-6 col-sm-4 col-md-4">sort by</label>
                          <div class="select-min col-6 col-sm-8 col-md-8">
                            <select class="form-control sort-by" name="sort" id="SortBy">
                              <option value="latest" {{ (request('sort') == 'latest')? 'selected':'' }}>Latest</option>
                              <option value="oldest" {{ (request('sort') == 'oldest')? 'selected': ''}}>Oldest</option>
                            </select>
                          </div>
                        </div>
                      </div>

                      <div class="col-sm-6 col-lg-2 show-filter">
                        <div class="row">
                         <label for="price-min" class="price-min col-6 col-sm-6 col-md-6 text-right">show</label>
                          <div class="select-min col-6 col-sm-6 col-md-6">
                            <select class="form-control sort-by"  name="listingQty" id="ListingQty">
                              <option value="12" {{ (request('items') == '12')? 'selected': ''}}>12</option>
                              <option value="8" {{(request('items') == '8')? 'selected': ''}}>8</option>
                            </select>
                          </div>
                        </div>
                      </div>
                  </div>
              </div>




          <div class="product-listings">
            <div class="row">
            @forelse($lrlistings as $listings)
              <div class="products-wrap gridView">
                  <a href="{{route('listing.read', ['id' => $listings->id])}}" class="products-inner">

                      <div class="product-img">
                        @if($listings->sale == 1)
                          <span class="sale"> sale </span>
                        @endif
                        <img src="@thumbnailImage($listings->image)" class="img-responsive">
                        <p><span>detail</span></p>
                      </div>
                      <div class="product-dis">
                        <ul class="product-type">
                          <li>{{ $listings->getCarMeta['fuel_type']}}</li>
                          <li>{{ $listings->getCarMeta['engine_size']}}</li>
                          <li>{{ $listings->getCarMeta['model']}}</li>
                          <li>{{ $listings->getCarMeta['condition']}}</li>
                          <li>{{ $listings->getCarMeta['car_type']}}</li>
                        </ul>
                        <div class="row">
                          <div class="col-sm-7 text-left car-name"><p class="h5">{{ $listings->name}}</p></div>
                          <div class="col-sm-5 text-right car-price"><p class="h2 bold">@money($listings->price)</p></div>
                        </div>
                      </div>
                  </a>
              </div>
@empty
<div class="col-md-12">
  <div class="alert alert-danger">
    No Listing Found
  </div>
</div>

@endforelse

{{ $lrlistings->appends(request()->query())->links('paginator') }}
<div class="ad-banner">
@include('partials.advertisement', ['slug' => 'vehicle-listing-bottom-horizontal'])
</div>


           </div>
               </div>
                    </div>
                         </div>
    </section>
@endsection

@section('footer_scripts')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

@endsection
