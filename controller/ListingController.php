<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Category;
use App\Country;
use App\City;
use App\Listing;
use App\ListingVariation;
use App\ListingContact;
use App\ListingImage;
use App\Order;
use App\SellerType;
use App\CarMake;
use App\CarModel;
use App\CarListingMeta;
use App\OrderLineItem;
use Gloudemans\Shoppingcart\Facades\Cart;
use Carbon\Carbon;
use App\PostCode;
use DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class ListingController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listings($category, $type = null)
    {

        $makes = CarMake::get();
        $models = CarModel::get();

        $lrlistings = Listing::has('getCarMeta')->with('getCarMeta')->paginate('12');



        return view('listing.index' , compact('makes','models','lrlistings'));
    }

    /**
     * Display a listing of Cars
     *
     * @return \Illuminate\Http\Response
     */
    public function index($category = null, Request $req)
    {

        $orderBy = 'DESC';
        $items = 12;

        if($req->has('sort') && request('sort') != ''){
            $sort = request('sort');

            if($sort == 'oldest'){
                $orderBy = 'ASC';
            } else {
                $orderBy = 'DESC';
            }
        }

        if($req->has('items') && request('items') != ''){
            $items = request('items');
        }



        $categories = Category::get();
        $makes = CarMake::get();
        $models = CarModel::get();

        if($category == null){
            $listingss = Listing::with('getCarMeta','getContact')->orderBy('start_date', $orderBy)->where('status', 'published');
        }else{
            $listings = Listing::where('category', $category)->paginate($items);
        }


        $lrlistings = Listing::has('getCarMeta')->with('getCarMeta')->whereRaw('start_date + interval duration day >= ?', Carbon::now()->format('Y-m-d') )->orderBy('start_date', $orderBy)->where('status','published')->paginate($items);

        /***
         * Compare date and Set listings to expired
         */

        Listing::whereRaw('start_date + interval duration day < ?', Carbon::now()->format('Y-m-d') )->where('status','published')->update(array('status'=> 'expired'));


        return view('listing.index', compact('categories','listingss','makes','models','lrlistings'));
    }

    /**
     * Displays a page where user can create listing
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $countries = Country::get();
        $categories = Category::get();
        $cities = City:: get();
        $makes = CarMake::get();
        $models = CarModel::get();
        $listingVariations = ListingVariation::where('status', 1)->where('listing_type', 'vehicle')->get();
        return view('listing.create', compact('categories', 'countries','cities','listingVariations', 'makes', 'models'));
    }

    /**
     * Display a single page of listing
     *
     * @return \Illuminate\Http\Response
     */
    public function read($id)
    {
        $categories = Category::get();
        $listing = Listing::where('id',$id)->firstOrFail();
        $listing_visits = Listing::where('id',$id)->increment('visits');
        $make =  $listing->getCarMeta['make'];
        $model = $listing->getCarMeta['model'];
        $sellerTypes = SellerType::where('id', $listing->type)->first();

        $relatedListing = Listing::with('getCarMeta')->where('status', 'published');


        $relatedListing->whereHas('getCarMeta', function($query) use ($make){
            $query->where('make', $make);
        });

        $relatedListing->whereHas('getCarMeta', function($query) use ($model){
            $query->where('model', $model);
        });

        $related = $relatedListing->take(3)->where('id', '!=' , $id)->get();
        $images = $listing->getImage->take(10);

        return view('listing.single', compact('categories', 'listing', 'sellerTypes','related', 'images'));
    }

    /**
     * Display Update/Edit page for listing
     *
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $id = base64_decode($id);
        $listing = Listing::where('id', $id)->firstOrFail();
        $metas = CarListingMeta::where('listing_id', $id)->first();
        $contact = ListingContact::where('listing_id', $id)->first();
        $images  = ListingImage::where('listing_id', $id)->take(9)->get();
        $countries = Country::get();
        $cities = City:: get();
        $makes = CarMake::get();
        $models = CarModel::get();
        $listingModel = CarModel::where('id', $metas->model )->firstOrFail();
        $categories = Category::get();
        return view('listing.edit',  compact('categories', 'countries','cities', 'makes', 'models','id','listing','contact','images','metas', 'listingModel'));
    }

    /**
     * When Triggered used to Delete Listings
     *
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $id = base64_decode($id);
        Listing::where('id', $id)->delete();

        return redirect()->back()->with(['delete-message','Listing Deleted Successfully']);
    }

    /**
     * Display a page to relist list listing.
     *
     * @return \Illuminate\Http\Response
     */
    public function relist($id)
    {
      $id = base64_decode($id);
      $listing = Listing::where('id', $id)->firstOrFail();
      $metas = CarListingMeta::where('listing_id', $id)->first();
      $contact = ListingContact::where('listing_id', $id)->first();
      $images  = ListingImage::where('listing_id', $id)->take(9)->get();
      $countries = Country::get();
      $cities = City:: get();
      $makes = CarMake::get();
      $models = CarModel::get();
      $listingModel = CarModel::where('id', $metas->model )->firstOrFail();
      $categories = Category::get();
      $listingVariations = ListingVariation::where('status', 1)->where('listing_type', 'vehicle')->get();
      return view('listing.relist',  compact('categories', 'countries','cities', 'makes', 'models','id','listing','contact','images','metas', 'listingModel', 'listingVariations'));
    }

    /**
     * Search the Listings of the resource. It has advance functionalities such as Search based on Distance and Postcode
     *
     * @return \Illuminate\Http\Response
     */

    public function search(Request $req){


        $orderBy = 'DESC';
        $items = 12;

        if($req->has('sort') && request('sort') != ''){
            $sort = request('sort');

            if($sort == 'oldest'){
                $orderBy = 'ASC';
            } else {
                $orderBy = 'DESC';
            }
        }

        if($req->has('items') && request('items') != ''){
            $items = request('items');
        }


        $makes = CarMake::get();
        $models = CarModel::get();
        $listings = Listing::with('getCarMeta','getContact')->orderBy('start_date', $orderBy)->where('status', 'published');





        if($req->has('make') && request('make') != ''){
            $make = request('make');
            $listings->whereHas('getCarMeta', function($query) use ($make){
                $query->where('make', $make);
            });
        }

        if($req->has('model') && request('model') != ''){
           $model = request('model');
           $listings->whereHas('getCarMeta', function($query) use ($model){
                $query->where('model', $model);
            });
        }


        if($req->has('fuel') && request('fuel') != ''){
            $fuel = request('fuel');
            $listings->whereHas('getCarMeta', function($query) use ($fuel){
                $query->where('fuel_type', $fuel);
            });
        }


        if($req->has('gearbox') && request('gearbox') != ''){
            $gearbox =request('gearbox');
            $listings->whereHas('getCarMeta', function($query) use ($gearbox) {
                $query->where('gearbox', $gearbox);
            });
        }

        if($req->has('price') && request('price') != ''){
            $price =request('price');
            $listings->whereHas('getCarMeta', function($query) use ($price){
                $query->where('price', $price);
            });
        }

        if($req->has('postcode') && request('postcode') != '' && $req->has('distance') && request('distance') != ''){
                $postcode = request('postcode');

                $distance = 1;
            if($req->has('distance') && request('distance') != ''){
                $distance = request('distance');
                $distance = $distance * 1.60934;
            }


            $postcode = preg_replace("/[^A-Za-z0-9]/", "", $postcode);
            $postcode = strtoupper($postcode);

            if(strlen($postcode) == 7){
                $codes = str_split($postcode,4);
                $outcode = $codes[0];
            } else {
                $codes = str_split($postcode,3);
                $outcode = $codes[0];
            }

                $coordinates = PostCode::where('outcode', $outcode)->firstOrFail();
                if($coordinates != null){
                    $latitude = $coordinates->lat;
                    $longitude = $coordinates->lng;



                $cities = PostCode::select(DB::raw('*, ( 6367 * acos( cos( radians('.$latitude.') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians('.$longitude.') ) + sin( radians('.$latitude.') ) * sin( radians( lat ) ) ) ) AS distance'))
                ->having('distance', '<', $distance)
                ->orderBy('distance')
                ->pluck('outcode');
                $listings->whereIn('outcode', $cities);
                }
        }


        $searchlistings = $listings->paginate($items);

        return view('listing.search', compact('searchlistings','makes','models'));



    }

    /**
     * Publish the listing to the database
     *
     * @return \Illuminate\Http\Response
     */
    public function publish(Request $req){

      /***
        * Validations for Create Listings - Combined Listing and listing COntact Table
        */

        $validatedData = $req->validate([
            'listing_title' => 'required|max:191',
            'seller_first_name' => 'required|max:191',
            'seller_last_name' => 'required|max:191',
            'seller_email' => 'required|max:191',
            'seller_phone' => 'required|max:15',
            'seller_location' => 'required|max:50',
            'postcode' => 'required|max:10',
        ]);

        /**
         * Listing Table
         */






        $name = request('listing_title');
        //
        $description = request('listing_description');
        $filepond =  request('filepond');
        $image = $filepond[0];
        $author = Auth::user()->id;
        $price = request('listing_price');
        $negotiable = request('negotiable');
        $hide_phone = request('hide_phone');
        $listing_type = request('listing_type');
        $category = 1;
        $status = 'pending';
        $listing_variation = request('listing_variation');
        $vehicle_make = request('vehicle_make');
        $vehicle_model = request('vehicle_model');
        $vehicle_fuel = request('vehicle_fuel');
        $vehicle_gearbox = request('vehicle_gearbox');
        $vehicle_condition = request('vehicle_condition');
        $vehicle_body = request('vehicle_body');
        $vehicle_engine_size = request('vehicle_engine_size');

        $postcode = request('postcode');
        $postcode = preg_replace("/[^A-Za-z0-9]/", "", $postcode);
        $postcode = strtoupper($postcode);

        if(strlen($postcode) == 7){
            $codes = str_split($postcode,4);
            $outcode = $codes[0];
        } else {
            $codes = str_split($postcode,3);
            $outcode = $codes[0];
        }





        /**
         * Listing Array
         */

        $listingArray = array(
            'name' => $name,
            'description' => $description,
            'image' => $image,
            'author' => $author,
            'price' => $price,
            'negotiable' => $negotiable,
            'hide_phone' => $hide_phone,
            'listing_type' => $listing_type,
            'status' => $status,
            'postcode' => $postcode,
            'outcode' => $outcode,
            'category' => $category
        );

        /**
         * Updates and get the Listing Id
         */

        $listing_id = Listing::insertGetId($listingArray);

        /**
         * Seller Information
         */

        $sellerArray = array('listing_id' => $listing_id,
        'user_id' => Auth::user()->id,
        'firstname' => request('seller_first_name'),
        'lastname' => request('seller_last_name'),
        'email' => request('seller_email'),
        'phone' => request('seller_phone'),
        'location' => request('seller_location'),
        'city' => request('city'),
        'remembered' => request('remember_me'));

        /**
         * updates the seller contact info
         */

        $sell_id = ListingContact::insertGetId($sellerArray);

        Listing::where('id', $listing_id)->update(['sell_id' => $sell_id]);


        /**
         * Vehicle Meta
         */

         $vehicle_array = array(
             'listing_id' => $listing_id,
             'make' => $vehicle_make,
             'model' => $vehicle_model,
             'price' => $price,
             'car_type' =>$vehicle_body,
             'fuel_type' => $vehicle_fuel,
             'condition' => $vehicle_condition,
             'distance' => 'null',
             'gearbox' => $vehicle_gearbox,
             'engine_size' => $vehicle_engine_size,
         );

         $carListingMeta = CarListingMeta::insertGetId($vehicle_array);



          if(count($filepond) > 0){

            foreach($filepond as $key=>$image){

                if($key != 0 ){
                  if($image != null){
                    ListingImage::insert([
                      'listing_id' => $listing_id,
                      'image' => $image
                    ]);
                  }
                }
            }
          }





        $listingVariation = ListingVariation::where('id', $listing_variation)->firstOrFail();

        $orderLineItemArray = array(
            'order_id' => null,
            'listing_id'=> $listing_id,
            'listing_variation' => $listing_variation,
            'type' => 'vehicle',
            'status' => 'pending',
            'price' => $listingVariation->price,
            'user' => Auth::user()->id
        );

        $lineItemId = OrderLineItem::insertGetId($orderLineItemArray);

        return redirect()->route('cart');
    }







    /***
     * Update publish Listing
     */


         /**
     * Publish the listing to the database
     *
     * @return \Illuminate\Http\Response
     */
    public function updatePublish(Request $req){



      $validatedData = $req->validate([
          'listing_title' => 'required|max:191',
          'seller_first_name' => 'required|max:191',
          'seller_last_name' => 'required|max:191',
          'seller_email' => 'required|max:191',
          'seller_phone' => 'required|max:15',
          'seller_location' => 'required|max:50',
          'postcode' => 'required|max:10'
      ]);

        /**
         * Listing Table
         */
        $id = request('listing_id');
        $name = request('listing_title');
        //$slug = Str::slug($name);
        $description = request('listing_description');
        $filepond =  request('filepond');
        $author = Auth::user()->id;
        $price = request('listing_price');
        $negotiable = request('negotiable');
        $hide_phone = request('hide_phone');
        $listing_type = request('listing_type');
        $category = 1;

        $vehicle_make = request('vehicle_make');
        $vehicle_model = request('vehicle_model');
        $vehicle_fuel = request('vehicle_fuel');
        $vehicle_gearbox = request('vehicle_gearbox');
        $vehicle_condition = request('vehicle_condition');
        $vehicle_body = request('vehicle_body');
        $vehicle_engine_size = request('vehicle_engine_size');


        $postcode = request('postcode');
        $postcode = preg_replace("/[^A-Za-z0-9]/", "", $postcode);
        $postcode = strtoupper($postcode);

        if(strlen($postcode) == 7){
            $codes = str_split($postcode,4);
            $outcode = $codes[0];
        } else {
            $codes = str_split($postcode,3);
            $outcode = $codes[0];
        }



        /**
         * Listing Array
         */

        $listingArray = array('name' => $name,
        'description' => $description,
        'author' => $author,
        'price' => $price,
        'negotiable' => $negotiable,
        'hide_phone' => $hide_phone,
        'listing_type' => $listing_type,
        'postcode' => $postcode,
        'outcode' => $outcode,
        'category' => $category);

        /**
         * Updates and get the Listing Id
         */

        Listing::where('id', $id)->update($listingArray);

        $listing_id = $id;
        /**
         * Seller Information
         */

        $sellerArray = array('listing_id' => $listing_id,
        'user_id' => Auth::user()->id,
        'firstname' => request('seller_first_name'),
        'lastname' => request('seller_last_name'),
        'email' => request('seller_email'),
        'phone' => request('seller_phone'),
        'location' => request('seller_location'),
        'city' => request('city'),
        'remembered' => request('remember_me'));

        /**
         * updates the seller contact info
         */


        $sell_id = ListingContact::updateOrCreate(array('listing_id' => $listing_id),$sellerArray);

        Listing::where('id', $listing_id)->update(['sell_id' => $sell_id]);


        /**
         * Vehicle Meta
         */

         $vehicle_array = array(
             'listing_id' => $listing_id,
             'make' => $vehicle_make,
             'model' => $vehicle_model,
             'price' => $price,
             'car_type' =>$vehicle_body,
             'fuel_type' => $vehicle_fuel,
             'condition' => $vehicle_condition,
             'distance' => 'null',
             'gearbox' => $vehicle_gearbox,
             'engine_size' => $vehicle_engine_size,
         );

         $carListingMeta = CarListingMeta::where('listing_id', $id)->update($vehicle_array);


            if($filepond != null){
                foreach($filepond as $key=>$image){
                      if($image != null){
                        ListingImage::insert([
                        'listing_id' => $id,
                        'image' => $image
                        ]);
                      }
                }
            }



        return redirect()->route('listing.read', ['id' => $id ]);
    }



    /**
     * Listing Image delete functionality
     */

    public function deleteListingImage($listing, $path){

        $listing = base64_decode($listing);
        $path = base64_decode($path);

        ListingImage::where('listing_id', $listing)->where('id', $path)->delete();

        Storage::disk('public')->delete($path);



        return redirect()->back()->with(['success','image deleted successfully']);
    }

    /**
     * Listing Image set main functionality
     */

    public function setMainListingImage($listing, $path){
        $listing = base64_decode($listing);
        $path = base64_decode($path);



        $image = Listing::where('id',$listing)->firstOrFail()->image;
        $swapImage = ListingImage::where('listing_id', $listing)->where('image', $path)->firstOrFail();



        Listing::where('id', $listing)->update(['image'=> $swapImage->image]);
        ListingImage::where('listing_id', $listing)->where('id', $path)->update(['image'=> $image]);


        return redirect()->back()->with(['success','Image set as Main']);
    }
}
