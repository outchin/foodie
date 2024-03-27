<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Item;
use App\Models\Setting;
use App\Models\Currency;
use App\Traits\Processor;
use App\Models\DataSetting;
use App\Models\TempProduct;
use App\Models\Translation;
use App\Models\AdminFeature;
use App\Models\RefundReason;
use Illuminate\Http\Request;
use App\Models\EmailTemplate;
use App\CentralLogics\Helpers;
use App\Models\BusinessSetting;
use App\Models\AdminTestimonial;
use App\Models\ReactTestimonial;
use App\Models\OrderCancelReason;
use Illuminate\Support\Facades\DB;
use App\Models\NotificationMessage;
use App\Http\Controllers\Controller;
use App\Models\AdminSpecialCriteria;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use App\Models\AdminPromotionalBanner;
use App\Models\FlutterSpecialCriteria;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BusinessSettingsController extends Controller
{
    use Processor;



    public function update_store(Request $request)
    {

        if ($request['product_approval'] == null){
            $this->product_approval_all();
        }
        BusinessSetting::updateOrInsert(['key' => 'cash_in_hand_overflow_store'], [
            'value' => $request['cash_in_hand_overflow_store'] ?? 0
        ]);
        BusinessSetting::updateOrInsert(['key' => 'cash_in_hand_overflow_store_amount'], [
            'value' => $request['cash_in_hand_overflow_store_amount']
        ]);
        BusinessSetting::updateOrInsert(['key' => 'min_amount_to_pay_store'], [
            'value' => $request['min_amount_to_pay_store']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'canceled_by_store'], [
            'value' => $request['canceled_by_store']
        ]);
        DB::table('business_settings')->updateOrInsert(['key' => 'toggle_store_registration'], [
            'value' => $request['store_self_registration']
        ]);
        DB::table('business_settings')->updateOrInsert(['key' => 'product_approval'], [
            'value' => $request['product_approval']
        ]);
        $values=[
            'Update_product_price'=> $request->Update_product_price ?? 0,
            'Add_new_product'=> $request->Add_new_product ?? 0,
            'Update_product_variation'=> $request->Update_product_variation ?? 0,
            'Update_anything_in_product_details'=> $request->Update_anything_in_product_details ?? 0,
        ];

        DB::table('business_settings')->updateOrInsert(['key' => 'product_approval_datas'], [
            'value' => json_encode($values)
        ]);


        DB::table('business_settings')->updateOrInsert(['key' => 'access_all_products'], [
            'value' => $request['access_all_products']
        ]);
        DB::table('business_settings')->updateOrInsert(['key' => 'product_gallery'], [
            'value' => $request['product_gallery']
        ]);



        Toastr::success(translate('messages.successfully_updated_to_changes_restart_app'));
        return back();
    }



    public function business_setup(Request $request)
    {

        if (env('APP_MODE') == 'demo') {
            Toastr::info(translate('messages.update_option_is_disable_for_demo'));
            return back();
        }


        DB::table('business_settings')->updateOrInsert(['key' => 'business_name'], [
            'value' => $request['store_name'] ?? 'Snow Delivery'
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'currency'], [
            'value' => $request['currency'] ?? 'MMK'
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'timezone'], [
            'value' => $request['timezone'] ?? 'Asia/Rangoon'
        ]);

        $curr_logo = BusinessSetting::where(['key' => 'logo'])->first();
        if ($request->has('logo')) {
            $image_name = Helpers::update('business/', $curr_logo->value, 'png', $request->file('logo'));
        } else {
            $image_name = $curr_logo['value'];
        }

        DB::table('business_settings')->updateOrInsert(['key' => 'logo'], [
            'value' => $image_name
        ]);

        $fav_icon = BusinessSetting::where(['key' => 'icon'])->first();
        if ($request->has('icon')) {
            $image_name = Helpers::update('business/', $fav_icon->value, 'png', $request->file('icon'));
        } else {
            $image_name = $fav_icon['value'];
        }

        if (session()->has('currency_symbol')) {
            session()->forget('currency_symbol');
        }
        if (session()->has('currency_code')) {
            session()->forget('currency_code');
        }
        if (session()->has('currency_symbol_position')) {
            session()->forget('currency_symbol_position');
        }

        DB::table('business_settings')->updateOrInsert(['key' => 'site_direction'], [
            'value' => $request['site_direction']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'icon'], [
            'value' => $image_name
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'phone'], [
            'value' => $request['phone'] ?? '959251098196'
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'email_address'], [
            'value' => $request['email'] ?? 'pyaesonephyoe714@gmail.com'
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'address'], [
            'value' => $request['address'] ?? '42nd 60x62 Street'
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'footer_text'], [
            'value' => $request['footer_text'] ?? 'All rights reserved @ 2024 Developed by TioTech'
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'cookies_text'], [
            'value' => $request['cookies_text'] ?? "Explore our site's flavors, where every click is a treat!"
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'minimum_shipping_charge'], [
            'value' => $request['minimum_shipping_charge']
        ]);
        DB::table('business_settings')->updateOrInsert(['key' => 'per_km_shipping_charge'], [
            'value' => $request['per_km_shipping_charge']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'currency_symbol_position'], [
            'value' => $request['currency_symbol_position'] ?? 'right'
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'tax_included'], [
            'value' => $request['tax_included']
        ]);
        DB::table('business_settings')->updateOrInsert(['key' => 'order_confirmation_model'], [
            'value' => $request['order_confirmation_model'] ?? 'deliveryman'
        ]);
        DB::table('business_settings')->updateOrInsert(['key' => 'partial_payment_status'], [
            'value' => $request['partial_payment_status']
        ]);
        DB::table('business_settings')->updateOrInsert(['key' => 'partial_payment_method'], [
            'value' => $request['partial_payment_method'] ?? 'both'
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'tax'], [
            'value' => $request['tax']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'admin_commission'], [
            'value' => $request['admin_commission']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'country'], [
            'value' => $request['country'] ?? 'MM'
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'default_location'], [
            'value' => json_encode(['lat' => $request['latitude'] ?? "20.151089", 'lng' => $request['longitude'] ?? "94.947628" ])
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'admin_order_notification'], [
            'value' => $request['admin_order_notification']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'order_notification_type'], [
            'value' => $request['order_notification_type'] ?? 'firebase'
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'free_delivery_over_status'], [
            'value' => $request['free_delivery_over_status'] ? $request['free_delivery_over_status'] : null
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'free_delivery_over'], [
            'value' => $request['free_delivery_over_status'] ? $request['free_delivery_over'] : null
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'additional_charge_status'], [
            'value' => $request['additional_charge_status'] ? $request['additional_charge_status'] : null
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'additional_charge_name'], [
            'value' => $request['additional_charge_name'] ? $request['additional_charge_name'] : null
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'additional_charge'], [
            'value' => $request['additional_charge'] ? $request['additional_charge'] : null
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'guest_checkout_status'], [
            'value' => $request['guest_checkout_status'] ? $request['guest_checkout_status'] : 0
        ]);

        // $languages = $request['language'];

        // if (in_array('en', $languages)) {
        //     unset($languages[array_search('en', $languages)]);
        // }
        // array_unshift($languages, 'en');

        // DB::table('business_settings')->updateOrInsert(['key' => 'language'], [
        //     'value' => json_encode($languages),
        // ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'timeformat'], [
            'value' => $request['time_format'] ?? '24'
        ]);



        DB::table('business_settings')->updateOrInsert(['key' => 'toggle_veg_non_veg'], [
            'value' => $request['vnv'] ?? '1'
        ]);


        DB::table('business_settings')->updateOrInsert(['key' => 'digit_after_decimal_point'], [
            'value' => $request['digit_after_decimal_point'] ?? '0'
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'delivery_charge_comission'], [
            'value' => $request['admin_comission_in_delivery_charge']
        ]);

        // DB::table('business_settings')->updateOrInsert(['key' => 'max_otp_hit'], [
        //     'value' => $request['max_otp_hit']
        // ]);
        // DB::table('business_settings')->updateOrInsert(['key' => 'max_otp_hit_time'], [
        //     'value' => $request['max_otp_hit_time']
        // ]);
        // DB::table('business_settings')->updateOrInsert(['key' => 'otp_interval_time'], [
        //     'value' => $request['otp_interval_time']
        // ]);


        Toastr::success(translate('messages.successfully_updated_to_changes_restart_app'));
        return back();
    }


    public function mail_config(Request $request)
    {
        if (env('APP_MODE') == 'demo') {
            Toastr::info(translate('messages.update_option_is_disable_for_demo'));
            return back();
        }
        BusinessSetting::updateOrInsert(
            ['key' => 'mail_config'],
            [
                'value' => json_encode([
                    "status" => $request['status'] ?? 0,
                    "name" => $request['name'],
                    "host" => $request['host'],
                    "driver" => $request['driver'],
                    "port" => $request['port'],
                    "username" => $request['username'],
                    "email_id" => $request['email'],
                    "encryption" => $request['encryption'],
                    "password" => $request['password']
                ]),
                'updated_at' => now()
            ]
        );
        Toastr::success(translate('messages.configuration_updated_successfully'));
        return back();
    }




    public function app_settings()
    {
        return view('admin-views.business-settings.app-settings');
    }

    public function landing_page_settings($tab)
    {
        if ($tab == 'index') {
            return view('admin-views.business-settings.landing-page-settings.index');
        } else if ($tab == 'links') {
            return view('admin-views.business-settings.landing-page-settings.links');
        } else if ($tab == 'speciality') {
            return view('admin-views.business-settings.landing-page-settings.speciality');
        } else if ($tab == 'testimonial') {
            return view('admin-views.business-settings.landing-page-settings.testimonial');
        } else if ($tab == 'feature') {
            return view('admin-views.business-settings.landing-page-settings.feature');
        } else if ($tab == 'joinas') {
            return view('admin-views.business-settings.landing-page-settings.join-as');
        } else if ($tab == 'download-section') {
            return view('admin-views.business-settings.landing-page-settings.download-app-section');
        } else if ($tab == 'promotion-banner') {
            return view('admin-views.business-settings.landing-page-settings.promotion-banner');
        } else if ($tab == 'module-section') {
            $module = Helpers::get_business_settings('module_section');
            return view('admin-views.business-settings.landing-page-settings.module-section', compact('module'));
        } else if ($tab == 'image') {
            return view('admin-views.business-settings.landing-page-settings.image');
        } else if ($tab == 'background-change') {
            return view('admin-views.business-settings.landing-page-settings.backgroundChange');
        } else if ($tab == 'web-app') {
            return view('admin-views.business-settings.landing-page-settings.web-app');
        } else if ($tab == 'react') {
            return view('admin-views.business-settings.landing-page-settings.react');
        } else if ($tab == 'react-feature') {
            return view('admin-views.business-settings.landing-page-settings.react_feature');
        }
    }



    private function update_data($request, $key_data){
        $data = DataSetting::firstOrNew(
            ['key' =>  $key_data,
                'type' =>  'admin_landing_page'],
        );

        $data->value = $request->{$key_data}[array_search('default', $request->lang)];
        $data->save();
        $default_lang = str_replace('_', '-', app()->getLocale());
        foreach ($request->lang as $index => $key) {
            if ($default_lang == $key && !($request->{$key_data}[$index])) {
                if ($key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type' => 'App\Models\DataSetting',
                            'translationable_id' => $data->id,
                            'locale' => $key,
                            'key' => $key_data
                        ],
                        ['value' => $data->getRawOriginal('value')]
                    );
                }
            } else {
                if ($request->{$key_data}[$index] && $key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type' => 'App\Models\DataSetting',
                            'translationable_id' => $data->id,
                            'locale' => $key,
                            'key' => $key_data
                        ],
                        ['value' => $request->{$key_data}[$index]]
                    );
                }
            }
        }

        return true;
    }


    private function policy_status_update($key_data , $status){
        $data = DataSetting::firstOrNew(
            ['key' =>  $key_data,
                'type' =>  'admin_landing_page'],
        );
        $data->value = $status;
        $data->save();

        return true;
    }


    public function terms_and_conditions()
    {
        $terms_and_conditions =DataSetting::withoutGlobalScope('translate')->where('type', 'admin_landing_page')->where('key', 'terms_and_conditions')->first();
        return view('admin-views.business-settings.terms-and-conditions', compact('terms_and_conditions'));
    }

    public function terms_and_conditions_update(Request $request)
    {
        $this->update_data($request , 'terms_and_conditions');
        Toastr::success(translate('messages.terms_and_condition_updated'));
        return back();
    }

    public function privacy_policy()
    {
        $privacy_policy =DataSetting::withoutGlobalScope('translate')->where('type', 'admin_landing_page')->where('key', 'privacy_policy')->first();
        return view('admin-views.business-settings.privacy-policy', compact('privacy_policy'));
    }

    public function privacy_policy_update(Request $request)
    {
        $this->update_data($request , 'privacy_policy');
        Toastr::success(translate('messages.privacy_policy_updated'));
        return back();
    }

    public function refund_policy()
    {
        $refund_policy =DataSetting::withoutGlobalScope('translate')->where('type', 'admin_landing_page')->where('key', 'refund_policy')->first();
        $refund_policy_status =DataSetting::where('type', 'admin_landing_page')->where('key','refund_policy_status')->first();
        return view('admin-views.business-settings.refund_policy', compact('refund_policy','refund_policy_status'));
    }

    public function refund_update(Request $request)
    {
        $this->update_data($request , 'refund_policy');
        Toastr::success(translate('messages.refund_policy_updated'));
        return back();
    }
    public function refund_policy_status($status)
    {
        $this->policy_status_update('refund_policy_status' , $status);
        return response()->json(['status'=>"changed"]);
    }

    public function shipping_policy()
    {

        $shipping_policy =DataSetting::withoutGlobalScope('translate')->where('type', 'admin_landing_page')->where('key', 'shipping_policy')->first();
        $shipping_policy_status =DataSetting::where('type', 'admin_landing_page')->where('key','shipping_policy_status')->first();
        return view('admin-views.business-settings.shipping_policy', compact('shipping_policy','shipping_policy_status'));
    }

    public function shipping_policy_update(Request $request)
    {
        $this->update_data($request , 'shipping_policy');
        Toastr::success(translate('messages.shipping_policy_updated'));
        return back();
    }


    public function shipping_policy_status($status)
    {
        $this->policy_status_update('shipping_policy_status' , $status);
        return response()->json(['status'=>"changed"]);
    }

    public function cancellation_policy()
    {
        $cancellation_policy =DataSetting::withoutGlobalScope('translate')->where('type', 'admin_landing_page')->where('key', 'cancellation_policy')->first();
        $cancellation_policy_status =DataSetting::where('type', 'admin_landing_page')->where('key','cancellation_policy_status')->first();
        return view('admin-views.business-settings.cancelation_policy',compact('cancellation_policy','cancellation_policy_status'));
    }

    public function cancellation_policy_update(Request $request)
    {
        $this->update_data($request , 'cancellation_policy');
        Toastr::success(translate('messages.cancellation_policy_updated'));
        return back();
    }

    public function cancellation_policy_status($status)
    {
        $this->policy_status_update('cancellation_policy_status' , $status);
        return response()->json(['status'=>"changed"]);
    }

    public function about_us()
    {
        $about_us =DataSetting::withoutGlobalScope('translate')->where('type', 'admin_landing_page')->where('key', 'about_us')->first();
        $about_title =DataSetting::withoutGlobalScope('translate')->where('type', 'admin_landing_page')->where('key', 'about_title')->first();
        return view('admin-views.business-settings.about-us', compact('about_us','about_title'));
    }

    public function about_us_update(Request $request)
    {
        // dd($request->all());
        $this->update_data($request , 'about_us');
        $this->update_data($request , 'about_title');
        Toastr::success(translate('messages.about_us_updated'));
        return back();
    }

    public function fcm_index()
    {
        $fcm_credentials = Helpers::get_business_settings('fcm_credentials');
        return view('admin-views.business-settings.fcm-index', compact('fcm_credentials'));
    }

    public function fcm_config()
    {
        $fcm_credentials = Helpers::get_business_settings('fcm_credentials');
        return view('admin-views.business-settings.fcm-config', compact('fcm_credentials'));
    }

    public function update_fcm(Request $request)
    {
        DB::table('business_settings')->updateOrInsert(['key' => 'fcm_project_id'], [
            'value' => 'snowdeliverywebapp'
        ]);
//            'value' => '$request['projectId']

        DB::table('business_settings')->updateOrInsert(['key' => 'push_notification_key'], [
            'value' => $request['push_notification_key'] ?? "AAAAlJziC6Q:APA91bH_Xe6Uf9ThZkjfaKG24bW59HCowmxVEWhB-TR3WrgwOrJ8MsAScYbVo4HvqjK0Zjv-ytR2P5oEEwQLShdKjLolSRZ2oTWm6ISnH4_MtSuwNNL7DUl5pyHwD7oAAGnpedqxOOd5"
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'fcm_credentials'], [
            'value' => json_encode([
                'apiKey' => $request->apiKey,
                'authDomain' => $request->authDomain,
                'projectId' => $request->projectId,
                'storageBucket' => $request->storageBucket,
                'messagingSenderId' => $request->messagingSenderId,
                'appId' => $request->appId,
                'measurementId' => $request->measurementId
            ])
        ]);
        Toastr::success(translate('messages.settings_updated'));
        return back();
    }

    public function update_fcm_messages(Request $request)
    {
        $notification = NotificationMessage::where('module_type', $request->module_type)->where('key', 'order_pending_message')->first();
        if ($notification == null) {
            $notification = new NotificationMessage();
        }

        $notification->key = 'order_pending_message';
        $notification->module_type = $request->module_type;
        $notification->message = $request->pending_message[array_search('en', $request->lang)];
        $notification->status = $request['pending_status'] == 1 ? 1 : 0;
        $notification->save();
        foreach ($request->lang as $index => $key) {
            if ($request->pending_message[$index]) {
                Translation::updateOrInsert(
                    [
                        'translationable_type'  => 'App\Models\NotificationMessage',
                        'translationable_id'    => $notification->id,
                        'locale'                => $key,
                        'key'                   => $notification->key
                    ],
                    ['value'                 => $request->pending_message[$index]]
                );
            }
        }

        $notification = NotificationMessage::where('module_type', $request->module_type)->where('key', 'order_confirmation_msg')->first();
        if ($notification == null) {
            $notification = new NotificationMessage();
        }

        $notification->key = 'order_confirmation_msg';
        $notification->module_type = $request->module_type;
        $notification->message = $request->confirm_message[array_search('en', $request->lang)];
        $notification->status = $request['confirm_status'] == 1 ? 1 : 0;
        $notification->save();
        foreach ($request->lang as $index => $key) {
            if ($request->confirm_message[$index]) {
                Translation::updateOrInsert(
                    [
                        'translationable_type'  => 'App\Models\NotificationMessage',
                        'translationable_id'    => $notification->id,
                        'locale'                => $key,
                        'key'                   => $notification->key
                    ],
                    ['value'                 => $request->confirm_message[$index]]
                );
            }
        }
        if ($request->module_type != 'parcel') {


            $notification = NotificationMessage::where('module_type', $request->module_type)->where('key', 'order_processing_message')->first();
            if ($notification == null) {
                $notification = new NotificationMessage();
            }

            $notification->key = 'order_processing_message';
            $notification->module_type = $request->module_type;
            $notification->message = $request->processing_message[array_search('en', $request->lang)];
            $notification->status = $request['processing_status'] == 1 ? 1 : 0;
            $notification->save();
            foreach ($request->lang as $index => $key) {
                if ($request->processing_message[$index]) {
                    Translation::updateOrInsert(
                        [
                            'translationable_type'  => 'App\Models\NotificationMessage',
                            'translationable_id'    => $notification->id,
                            'locale'                => $key,
                            'key'                   => $notification->key
                        ],
                        ['value'                 => $request->processing_message[$index]]
                    );
                }
            }

            $notification = NotificationMessage::where('module_type', $request->module_type)->where('key', 'order_handover_message')->first();
            if ($notification == null) {
                $notification = new NotificationMessage();
            }

            $notification->key = 'order_handover_message';
            $notification->module_type = $request->module_type;
            $notification->message = $request->order_handover_message[array_search('en', $request->lang)];
            $notification->status = $request['order_handover_message_status'] == 1 ? 1 : 0;
            $notification->save();
            foreach ($request->lang as $index => $key) {
                if ($request->order_handover_message[$index]) {
                    Translation::updateOrInsert(
                        [
                            'translationable_type'  => 'App\Models\NotificationMessage',
                            'translationable_id'    => $notification->id,
                            'locale'                => $key,
                            'key'                   => $notification->key
                        ],
                        ['value'                 => $request->order_handover_message[$index]]
                    );
                }
            }

            $notification = NotificationMessage::where('module_type', $request->module_type)->where('key', 'order_refunded_message')->first();
            if ($notification == null) {
                $notification = new NotificationMessage();
            }

            $notification->key = 'order_refunded_message';
            $notification->module_type = $request->module_type;
            $notification->message = $request->order_refunded_message[array_search('en', $request->lang)];
            $notification->status = $request['order_refunded_message_status'] == 1 ? 1 : 0;
            $notification->save();
            foreach ($request->lang as $index => $key) {
                if ($request->order_refunded_message[$index]) {
                    Translation::updateOrInsert(
                        [
                            'translationable_type'  => 'App\Models\NotificationMessage',
                            'translationable_id'    => $notification->id,
                            'locale'                => $key,
                            'key'                   => $notification->key
                        ],
                        ['value'                 => $request->order_refunded_message[$index]]
                    );
                }
            }

            $notification = NotificationMessage::where('module_type', $request->module_type)->where('key', 'refund_request_canceled')->first();

            if ($notification == null) {
                $notification = new NotificationMessage();
            }

            $notification->key = 'refund_request_canceled';
            $notification->module_type = $request->module_type;
            $notification->message = $request->refund_request_canceled[array_search('en', $request->lang)];
            $notification->status = $request['refund_request_canceled_status'] == 1 ? 1 : 0;
            $notification->save();
            foreach ($request->lang as $index => $key) {
                if ($request->refund_request_canceled[$index]) {
                    Translation::updateOrInsert(
                        [
                            'translationable_type'  => 'App\Models\NotificationMessage',
                            'translationable_id'    => $notification->id,
                            'locale'                => $key,
                            'key'                   => $notification->key
                        ],
                        ['value'                 => $request->refund_request_canceled[$index]]
                    );
                }
            }
        }


        $notification = NotificationMessage::where('module_type', $request->module_type)->where('key', 'out_for_delivery_message')->first();
        if ($notification == null) {
            $notification = new NotificationMessage();
        }

        $notification->key = 'out_for_delivery_message';
        $notification->module_type = $request->module_type;
        $notification->message = $request->out_for_delivery_message[array_search('en', $request->lang)];
        $notification->status = $request['out_for_delivery_status'] == 1 ? 1 : 0;
        $notification->save();
        foreach ($request->lang as $index => $key) {
            if ($request->out_for_delivery_message[$index]) {
                Translation::updateOrInsert(
                    [
                        'translationable_type'  => 'App\Models\NotificationMessage',
                        'translationable_id'    => $notification->id,
                        'locale'                => $key,
                        'key'                   => $notification->key
                    ],
                    ['value'                 => $request->out_for_delivery_message[$index]]
                );
            }
        }

        $notification = NotificationMessage::where('module_type', $request->module_type)->where('key', 'order_delivered_message')->first();
        if ($notification == null) {
            $notification = new NotificationMessage();
        }

        $notification->key = 'order_delivered_message';
        $notification->module_type = $request->module_type;
        $notification->message = $request->delivered_message[array_search('en', $request->lang)];
        $notification->status = $request['delivered_status'] == 1 ? 1 : 0;
        $notification->save();
        foreach ($request->lang as $index => $key) {
            if ($request->delivered_message[$index]) {
                Translation::updateOrInsert(
                    [
                        'translationable_type'  => 'App\Models\NotificationMessage',
                        'translationable_id'    => $notification->id,
                        'locale'                => $key,
                        'key'                   => $notification->key
                    ],
                    ['value'                 => $request->delivered_message[$index]]
                );
            }
        }

        $notification = NotificationMessage::where('module_type', $request->module_type)->where('key', 'delivery_boy_assign_message')->first();
        if ($notification == null) {
            $notification = new NotificationMessage();
        }

        $notification->key = 'delivery_boy_assign_message';
        $notification->module_type = $request->module_type;
        $notification->message = $request->delivery_boy_assign_message[array_search('en', $request->lang)];
        $notification->status = $request['delivery_boy_assign_status'] == 1 ? 1 : 0;
        $notification->save();
        foreach ($request->lang as $index => $key) {
            if ($request->delivery_boy_assign_message[$index]) {
                Translation::updateOrInsert(
                    [
                        'translationable_type'  => 'App\Models\NotificationMessage',
                        'translationable_id'    => $notification->id,
                        'locale'                => $key,
                        'key'                   => $notification->key
                    ],
                    ['value'                 => $request->delivery_boy_assign_message[$index]]
                );
            }
        }

        $notification = NotificationMessage::where('module_type', $request->module_type)->where('key', 'delivery_boy_delivered_message')->first();
        if ($notification == null) {
            $notification = new NotificationMessage();
        }

        $notification->key = 'delivery_boy_delivered_message';
        $notification->module_type = $request->module_type;
        $notification->message = $request->delivery_boy_delivered_message[array_search('en', $request->lang)];
        $notification->status = $request['delivery_boy_delivered_status'] == 1 ? 1 : 0;
        $notification->save();
        foreach ($request->lang as $index => $key) {
            if ($request->delivery_boy_delivered_message[$index]) {
                Translation::updateOrInsert(
                    [
                        'translationable_type'  => 'App\Models\NotificationMessage',
                        'translationable_id'    => $notification->id,
                        'locale'                => $key,
                        'key'                   => $notification->key
                    ],
                    ['value'                 => $request->delivery_boy_delivered_message[$index]]
                );
            }
        }

        $notification = NotificationMessage::where('module_type', $request->module_type)->where('key', 'order_cancled_message')->first();
        if ($notification == null) {
            $notification = new NotificationMessage();
        }

        $notification->key = 'order_cancled_message';
        $notification->module_type = $request->module_type;
        $notification->message = $request->order_cancled_message[array_search('en', $request->lang)];
        $notification->status = $request['order_cancled_message_status'] == 1 ? 1 : 0;
        $notification->save();
        foreach ($request->lang as $index => $key) {
            if ($request->order_cancled_message[$index]) {
                Translation::updateOrInsert(
                    [
                        'translationable_type'  => 'App\Models\NotificationMessage',
                        'translationable_id'    => $notification->id,
                        'locale'                => $key,
                        'key'                   => $notification->key
                    ],
                    ['value'                 => $request->order_cancled_message[$index]]
                );
            }
        }

        $notification = NotificationMessage::where('module_type', $request->module_type)->where('key', 'offline_order_accept_message')->first();
        if ($notification == null) {
            $notification = new NotificationMessage();
        }

        $notification->key = 'offline_order_accept_message';
        $notification->module_type = $request->module_type;
        $notification->message = $request->offline_order_accept_message[array_search('en', $request->lang)];
        $notification->status = $request['offline_order_accept_message_status'] == 1 ? 1 : 0;
        $notification->save();
        foreach ($request->lang as $index => $key) {
            if ($request->offline_order_accept_message[$index]) {
                Translation::updateOrInsert(
                    [
                        'translationable_type'  => 'App\Models\NotificationMessage',
                        'translationable_id'    => $notification->id,
                        'locale'                => $key,
                        'key'                   => $notification->key
                    ],
                    ['value'                 => $request->offline_order_accept_message[$index]]
                );
            }
        }

        $notification = NotificationMessage::where('module_type', $request->module_type)->where('key', 'offline_order_deny_message')->first();
        if ($notification == null) {
            $notification = new NotificationMessage();
        }

        $notification->key = 'offline_order_deny_message';
        $notification->module_type = $request->module_type;
        $notification->message = $request->offline_order_deny_message[array_search('en', $request->lang)];
        $notification->status = $request['offline_order_deny_message_status'] == 1 ? 1 : 0;
        $notification->save();
        foreach ($request->lang as $index => $key) {
            if ($request->offline_order_deny_message[$index]) {
                Translation::updateOrInsert(
                    [
                        'translationable_type'  => 'App\Models\NotificationMessage',
                        'translationable_id'    => $notification->id,
                        'locale'                => $key,
                        'key'                   => $notification->key
                    ],
                    ['value'                 => $request->offline_order_deny_message[$index]]
                );
            }
        }


        Toastr::success(translate('messages.message_updated'));
        return back();
    }

    public function location_index()
    {
        return view('admin-views.business-settings.location-index');
    }

    public function location_setup(Request $request)
    {
        $store = Helpers::get_store_id();
        $store->latitude = $request['latitude'];
        $store->longitude = $request['longitude'];
        $store->save();

        Toastr::success(translate('messages.settings_updated'));
        return back();
    }

    public function config_setup()
    {
        return view('admin-views.business-settings.config');
    }

    public function config_update(Request $request)
    {
        DB::table('business_settings')->updateOrInsert(['key' => 'map_api_key'], [
            'value' => $request['map_api_key']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'map_api_key_server'], [
            'value' => $request['map_api_key_server']
        ]);

        Toastr::success(translate('messages.config_data_updated'));
        return back();
    }

    public function toggle_settings($key, $value)
    {
        DB::table('business_settings')->updateOrInsert(['key' => $key], [
            'value' => $value
        ]);

        Toastr::success(translate('messages.app_settings_updated'));
        return back();
    }

    public function viewSocialLogin()
    {
        $data = BusinessSetting::where('key', 'social_login')->first();
        if (!$data) {
            Helpers::insert_business_settings_key('social_login', '[{"login_medium":"google","client_id":"","client_secret":"","status":"0"},{"login_medium":"facebook","client_id":"","client_secret":"","status":""}]');
            $data = BusinessSetting::where('key', 'social_login')->first();
        }
        $apple = BusinessSetting::where('key', 'apple_login')->first();
        if (!$apple) {
            Helpers::insert_business_settings_key('apple_login', '[{"login_medium":"apple","client_id":"","client_secret":"","team_id":"","key_id":"","service_file":"","redirect_url":"","status":""}]');
            $apple = BusinessSetting::where('key', 'apple_login')->first();
        }
        $appleLoginServices = json_decode($apple->value, true);
        $socialLoginServices = json_decode($data->value, true);
        return view('admin-views.business-settings.social-login.view', compact('socialLoginServices', 'appleLoginServices'));
    }

    public function updateSocialLogin($service, Request $request)
    {
        $socialLogin = BusinessSetting::where('key', 'social_login')->first();
        $credential_array = [];
        foreach (json_decode($socialLogin['value'], true) as $key => $data) {
            if ($data['login_medium'] == $service) {
                $cred = [
                    'login_medium' => $service,
                    'client_id' => $request['client_id'],
                    'client_secret' => $request['client_secret'],
                    'status' => $request['status'],
                ];
                array_push($credential_array, $cred);
            } else {
                array_push($credential_array, $data);
            }
        }
        BusinessSetting::where('key', 'social_login')->update([
            'value' => $credential_array
        ]);

        Toastr::success(translate('messages.credential_updated', ['service' => $service]));
        return redirect()->back();
    }
    public function updateAppleLogin($service, Request $request)
    {
        $appleLogin = BusinessSetting::where('key', 'apple_login')->first();
        $credential_array = [];
        if ($request->hasfile('service_file')) {
            $fileName = Helpers::upload('apple-login/', 'p8', $request->file('service_file'));
        }
        foreach (json_decode($appleLogin['value'], true) as $key => $data) {
            if ($data['login_medium'] == $service) {
                $cred = [
                    'login_medium' => $service,
                    'client_id' => $request['client_id'],
                    'client_secret' => $request['client_secret'],
                    'status' => $request['status'],
                    'team_id' => $request['team_id'],
                    'key_id' => $request['key_id'],
                    'service_file' => isset($fileName) ? $fileName : $data['service_file'],
                    'redirect_url' => $request['redirect_url'],
                ];
                array_push($credential_array, $cred);
            } else {
                array_push($credential_array, $data);
            }
        }
        BusinessSetting::where('key', 'apple_login')->update([
            'value' => $credential_array
        ]);

        Toastr::success(translate('messages.credential_updated', ['service' => $service]));
        return redirect()->back();
    }

    //recaptcha
    public function recaptcha_index(Request $request)
    {
        return view('admin-views.business-settings.recaptcha-index');
    }

    public function recaptcha_update(Request $request)
    {
        DB::table('business_settings')->updateOrInsert(['key' => 'recaptcha'], [
            'key' => 'recaptcha',
            'value' => json_encode([
                'status' => $request['status'],
                'site_key' => $request['site_key'],
                'secret_key' => $request['secret_key']
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Toastr::success(translate('messages.updated_successfully'));
        return back();
    }
    //Send Mail
    public function send_mail(Request $request)
    {
        if (env('APP_MODE') == 'demo') {
            Toastr::info(translate('messages.update_option_is_disable_for_demo'));
            return back();
        }
        $response_flag = 0;
        try {
            Mail::to($request->email)->send(new \App\Mail\TestEmailSender());
            $response_flag = 1;
        } catch (\Exception $exception) {
            info($exception->getMessage());
            $response_flag = 2;
        }

        return response()->json(['success' => $response_flag]);
    }


    public function site_direction(Request $request)
    {
        if (env('APP_MODE') == 'demo') {
            session()->put('site_direction', ($request->status == 1 ? 'ltr' : 'rtl'));
            return response()->json();
        }
        if ($request->status == 1) {
            DB::table('business_settings')->updateOrInsert(['key' => 'site_direction'], [
                'value' => 'ltr'
            ]);
        } else {
            DB::table('business_settings')->updateOrInsert(['key' => 'site_direction'], [
                'value' => 'rtl'
            ]);
        }
        return;
    }

    public function admin_landing_page_settings($tab)
    {
        if ($tab == 'fixed-data') {
            return view('admin-views.business-settings.landing-page-settings.admin-fixed-data');
        } else if ($tab == 'promotional-section') {
            return view('admin-views.business-settings.landing-page-settings.admin-promotional-section');
        } else if ($tab == 'feature-list') {
            return view('admin-views.business-settings.landing-page-settings.admin-feature-list');
        } else if ($tab == 'earn-money') {
            return view('admin-views.business-settings.landing-page-settings.admin-earn-money');
        } else if ($tab == 'why-choose-us') {
            return view('admin-views.business-settings.landing-page-settings.admin-landing-why-choose');
        } else if ($tab == 'download-apps') {
            return view('admin-views.business-settings.landing-page-settings.admin-landing-download-apps');
        } else if ($tab == 'testimonials') {
            return view('admin-views.business-settings.landing-page-settings.admin-landing-testimonial');
        } else if ($tab == 'contact-us') {
            return view('admin-views.business-settings.landing-page-settings.admin-landing-contact');
        } else if ($tab == 'background-color') {
            return view('admin-views.business-settings.landing-page-settings.admin-landing-background-color');
        }
    }

    public function update_admin_landing_page_settings(Request $request, $tab)
    {
        if (env('APP_MODE') == 'demo') {
            Toastr::info(translate('messages.update_option_is_disable_for_demo'));
            return back();
        }

        if ($tab == 'fixed-data') {
            $fixed_header_title = DataSetting::where('type', 'admin_landing_page')->where('key', 'fixed_header_title')->first();
            if ($fixed_header_title == null) {
                $fixed_header_title = new DataSetting();
            }

            $fixed_header_title->key = 'fixed_header_title';
            $fixed_header_title->type = 'admin_landing_page';
            $fixed_header_title->value = $request->fixed_header_title[array_search('default', $request->lang)];
            $fixed_header_title->save();

            $fixed_header_sub_title = DataSetting::where('type', 'admin_landing_page')->where('key', 'fixed_header_sub_title')->first();
            if ($fixed_header_sub_title == null) {
                $fixed_header_sub_title = new DataSetting();
            }

            $fixed_header_sub_title->key = 'fixed_header_sub_title';
            $fixed_header_sub_title->type = 'admin_landing_page';
            $fixed_header_sub_title->value = $request->fixed_header_sub_title[array_search('default', $request->lang)];
            $fixed_header_sub_title->save();

            $fixed_module_title = DataSetting::where('type', 'admin_landing_page')->where('key', 'fixed_module_title')->first();
            if ($fixed_module_title == null) {
                $fixed_module_title = new DataSetting();
            }

            $fixed_module_title->key = 'fixed_module_title';
            $fixed_module_title->type = 'admin_landing_page';
            $fixed_module_title->value = $request->fixed_module_title[array_search('default', $request->lang)];
            $fixed_module_title->save();

            $fixed_module_sub_title = DataSetting::where('type', 'admin_landing_page')->where('key', 'fixed_module_sub_title')->first();
            if ($fixed_module_sub_title == null) {
                $fixed_module_sub_title = new DataSetting();
            }

            $fixed_module_sub_title->key = 'fixed_module_sub_title';
            $fixed_module_sub_title->type = 'admin_landing_page';
            $fixed_module_sub_title->value = $request->fixed_module_sub_title[array_search('default', $request->lang)];
            $fixed_module_sub_title->save();

            $fixed_referal_title = DataSetting::where('type', 'admin_landing_page')->where('key', 'fixed_referal_title')->first();
            if ($fixed_referal_title == null) {
                $fixed_referal_title = new DataSetting();
            }

            $fixed_referal_title->key = 'fixed_referal_title';
            $fixed_referal_title->type = 'admin_landing_page';
            $fixed_referal_title->value = $request->fixed_referal_title[array_search('default', $request->lang)];
            $fixed_referal_title->save();

            $fixed_referal_sub_title = DataSetting::where('type', 'admin_landing_page')->where('key', 'fixed_referal_sub_title')->first();
            if ($fixed_referal_sub_title == null) {
                $fixed_referal_sub_title = new DataSetting();
            }

            $fixed_referal_sub_title->key = 'fixed_referal_sub_title';
            $fixed_referal_sub_title->type = 'admin_landing_page';
            $fixed_referal_sub_title->value = $request->fixed_referal_sub_title[array_search('default', $request->lang)];
            $fixed_referal_sub_title->save();

            $fixed_newsletter_title = DataSetting::where('type', 'admin_landing_page')->where('key', 'fixed_newsletter_title')->first();
            if ($fixed_newsletter_title == null) {
                $fixed_newsletter_title = new DataSetting();
            }

            $fixed_newsletter_title->key = 'fixed_newsletter_title';
            $fixed_newsletter_title->type = 'admin_landing_page';
            $fixed_newsletter_title->value = $request->fixed_newsletter_title[array_search('default', $request->lang)];
            $fixed_newsletter_title->save();

            $fixed_newsletter_sub_title = DataSetting::where('type', 'admin_landing_page')->where('key', 'fixed_newsletter_sub_title')->first();
            if ($fixed_newsletter_sub_title == null) {
                $fixed_newsletter_sub_title = new DataSetting();
            }

            $fixed_newsletter_sub_title->key = 'fixed_newsletter_sub_title';
            $fixed_newsletter_sub_title->type = 'admin_landing_page';
            $fixed_newsletter_sub_title->value = $request->fixed_newsletter_sub_title[array_search('default', $request->lang)];
            $fixed_newsletter_sub_title->save();

            $fixed_footer_article_title = DataSetting::where('type', 'admin_landing_page')->where('key', 'fixed_footer_article_title')->first();
            if ($fixed_footer_article_title == null) {
                $fixed_footer_article_title = new DataSetting();
            }

            $fixed_footer_article_title->key = 'fixed_footer_article_title';
            $fixed_footer_article_title->type = 'admin_landing_page';
            $fixed_footer_article_title->value = $request->fixed_footer_article_title[array_search('default', $request->lang)];
            $fixed_footer_article_title->save();
            // dd($fixed_module_sub_title?->getRawOriginal('value'));

            $default_lang = str_replace('_', '-', app()->getLocale());
            foreach ($request->lang as $index => $key) {
                if ($default_lang == $key && !($request->fixed_header_title[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $fixed_header_title->id,
                                'locale' => $key,
                                'key' => 'fixed_header_title'
                            ],
                            ['value' => $fixed_header_title?->getRawOriginal('value')]
                        );
                    }
                } else {
                    if ($request->fixed_header_title[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $fixed_header_title->id,
                                'locale' => $key,
                                'key' => 'fixed_header_title'
                            ],
                            ['value' => $request->fixed_header_title[$index]]
                        );
                    }
                }
                if ($default_lang == $key && !($request->fixed_header_sub_title[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $fixed_header_sub_title->id,
                                'locale' => $key,
                                'key' => 'fixed_header_sub_title'
                            ],
                            ['value' => $fixed_header_sub_title?->getRawOriginal('value')]
                        );
                    }
                } else {
                    if ($request->fixed_header_sub_title[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $fixed_header_sub_title->id,
                                'locale' => $key,
                                'key' => 'fixed_header_sub_title'
                            ],
                            ['value' => $request->fixed_header_sub_title[$index]]
                        );
                    }
                }
                if ($default_lang == $key && !($request->fixed_module_title[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $fixed_module_title->id,
                                'locale' => $key,
                                'key' => 'fixed_module_title'
                            ],
                            ['value' => $fixed_module_title?->getRawOriginal('value')]
                        );
                    }
                } else {
                    if ($request->fixed_module_title[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $fixed_module_title->id,
                                'locale' => $key,
                                'key' => 'fixed_module_title'
                            ],
                            ['value' => $request->fixed_module_title[$index]]
                        );
                    }
                }
                if ($default_lang == $key && !($request->fixed_module_sub_title[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $fixed_module_sub_title->id,
                                'locale' => $key,
                                'key' => 'fixed_module_sub_title'
                            ],
                            ['value' => $fixed_module_sub_title?->getRawOriginal('value')]
                        );
                    }
                } else {
                    if ($request->fixed_module_sub_title[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $fixed_module_sub_title->id,
                                'locale' => $key,
                                'key' => 'fixed_module_sub_title'
                            ],
                            ['value' => $request->fixed_module_sub_title[$index]]
                        );
                    }
                }
                if ($default_lang == $key && !($request->fixed_referal_title[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $fixed_referal_title->id,
                                'locale' => $key,
                                'key' => 'fixed_referal_title'
                            ],
                            ['value' => $fixed_referal_title?->getRawOriginal('value')]
                        );
                    }
                } else {
                    if ($request->fixed_referal_title[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $fixed_referal_title->id,
                                'locale' => $key,
                                'key' => 'fixed_referal_title'
                            ],
                            ['value' => $request->fixed_referal_title[$index]]
                        );
                    }
                }
                if ($default_lang == $key && !($request->fixed_referal_sub_title[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $fixed_referal_sub_title->id,
                                'locale' => $key,
                                'key' => 'fixed_referal_sub_title'
                            ],
                            ['value' => $fixed_referal_sub_title?->getRawOriginal('value')]
                        );
                    }
                } else {
                    if ($request->fixed_referal_sub_title[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $fixed_referal_sub_title->id,
                                'locale' => $key,
                                'key' => 'fixed_referal_sub_title'
                            ],
                            ['value' => $request->fixed_referal_sub_title[$index]]
                        );
                    }
                }
                if ($default_lang == $key && !($request->fixed_newsletter_title[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $fixed_newsletter_title->id,
                                'locale' => $key,
                                'key' => 'fixed_newsletter_title'
                            ],
                            ['value' => $fixed_newsletter_title?->getRawOriginal('value')]
                        );
                    }
                } else {
                    if ($request->fixed_newsletter_title[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $fixed_newsletter_title->id,
                                'locale' => $key,
                                'key' => 'fixed_newsletter_title'
                            ],
                            ['value' => $request->fixed_newsletter_title[$index]]
                        );
                    }
                }
                if ($default_lang == $key && !($request->fixed_newsletter_sub_title[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $fixed_newsletter_sub_title->id,
                                'locale' => $key,
                                'key' => 'fixed_newsletter_sub_title'
                            ],
                            ['value' => $fixed_newsletter_sub_title?->getRawOriginal('value')]
                        );
                    }
                } else {
                    if ($request->fixed_newsletter_sub_title[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $fixed_newsletter_sub_title->id,
                                'locale' => $key,
                                'key' => 'fixed_newsletter_sub_title'
                            ],
                            ['value' => $request->fixed_newsletter_sub_title[$index]]
                        );
                    }
                }
                if ($default_lang == $key && !($request->fixed_footer_article_title[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $fixed_footer_article_title->id,
                                'locale' => $key,
                                'key' => 'fixed_footer_article_title'
                            ],
                            ['value' => $fixed_footer_article_title?->getRawOriginal('value')]
                        );
                    }
                } else {
                    if ($request->fixed_footer_article_title[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $fixed_footer_article_title->id,
                                'locale' => $key,
                                'key' => 'fixed_footer_article_title'
                            ],
                            ['value' => $request->fixed_footer_article_title[$index]]
                        );
                    }
                }
            }

            DB::table('data_settings')->updateOrInsert(['key' => 'fixed_link', 'type' => 'admin_landing_page'], [
                'value' => json_encode([
                    'web_app_url_status' => $request['web_app_url_status'],
                    'web_app_url' => $request['web_app_url'],
                ])
            ]);
            Toastr::success(translate('messages.landing_page_text_updated'));
        } elseif ($tab == 'promotional-section') {
            $request->validate([
                'title' => 'required',
                'sub_title' => 'required',
                'image' => 'required',
            ]);
            if($request->title[array_search('default', $request->lang)] == ''){
                Toastr::error(translate('default_data_is_required'));
                return back();
            }
            $banner = new AdminPromotionalBanner();
            $banner->title = $request->title[array_search('default', $request->lang)];
            $banner->sub_title = $request->sub_title[array_search('default', $request->lang)];
            $banner->image = Helpers::upload('promotional_banner/', 'png', $request->file('image'));
            $banner->save();
            $default_lang = str_replace('_', '-', app()->getLocale());
            $data = [];
            foreach ($request->lang as $index => $key) {
                if ($default_lang == $key && !($request->title[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type'  => 'App\Models\AdminPromotionalBanner',
                                'translationable_id'    => $banner->id,
                                'locale'                => $key,
                                'key'                   => 'title'
                            ],
                            ['value'                 => $banner->title]
                        );
                    }
                } else {

                    if ($request->title[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type'  => 'App\Models\AdminPromotionalBanner',
                                'translationable_id'    => $banner->id,
                                'locale'                => $key,
                                'key'                   => 'title'
                            ],
                            ['value'                 => $request->title[$index]]
                        );
                    }
                }
                if ($default_lang == $key && !($request->sub_title[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type'  => 'App\Models\AdminPromotionalBanner',
                                'translationable_id'    => $banner->id,
                                'locale'                => $key,
                                'key'                   => 'sub_title'
                            ],
                            ['value'                 => $banner->sub_title]
                        );
                    }
                } else {

                    if ($request->sub_title[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type'  => 'App\Models\AdminPromotionalBanner',
                                'translationable_id'    => $banner->id,
                                'locale'                => $key,
                                'key'                   => 'sub_title'
                            ],
                            ['value'                 => $request->sub_title[$index]]
                        );
                    }
                }
            }

            Toastr::success(translate('messages.banner_added_successfully'));
            return back();
        } elseif ($tab == 'feature-list') {
            $request->validate([
                'title' => 'required',
                'sub_title' => 'required',
                'image' => 'required',
            ]);
            if($request->title[array_search('default', $request->lang)] == ''){
                Toastr::error(translate('default_data_is_required'));
                return back();
            }
            $feature = new AdminFeature();
            $feature->title = $request->title[array_search('default', $request->lang)];
            $feature->sub_title = $request->sub_title[array_search('default', $request->lang)];
            $feature->image = Helpers::upload('admin_feature/', 'png', $request->file('image'));
            $feature->save();
            $default_lang = str_replace('_', '-', app()->getLocale());
            $data = [];
            foreach ($request->lang as $index => $key) {
                if ($default_lang == $key && !($request->title[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type'  => 'App\Models\AdminFeature',
                                'translationable_id'    => $feature->id,
                                'locale'                => $key,
                                'key'                   => 'title'
                            ],
                            ['value'                 => $feature->title]
                        );
                    }
                } else {

                    if ($request->title[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type'  => 'App\Models\AdminFeature',
                                'translationable_id'    => $feature->id,
                                'locale'                => $key,
                                'key'                   => 'title'
                            ],
                            ['value'                 => $request->title[$index]]
                        );
                    }
                }
                if ($default_lang == $key && !($request->sub_title[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type'  => 'App\Models\AdminFeature',
                                'translationable_id'    => $feature->id,
                                'locale'                => $key,
                                'key'                   => 'sub_title'
                            ],
                            ['value'                 => $feature->sub_title]
                        );
                    }
                } else {

                    if ($request->sub_title[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type'  => 'App\Models\AdminFeature',
                                'translationable_id'    => $feature->id,
                                'locale'                => $key,
                                'key'                   => 'sub_title'
                            ],
                            ['value'                 => $request->sub_title[$index]]
                        );
                    }
                }
            }

            Toastr::success(translate('messages.feature_added_successfully'));
        } elseif ($tab == 'feature-title') {
            $feature_title = DataSetting::where('type', 'admin_landing_page')->where('key', 'feature_title')->first();
            if ($feature_title == null) {
                $feature_title = new DataSetting();
            }

            $feature_title->key = 'feature_title';
            $feature_title->type = 'admin_landing_page';
            $feature_title->value = $request->feature_title[array_search('default', $request->lang)];
            $feature_title->save();

            $feature_short_description = DataSetting::where('type', 'admin_landing_page')->where('key', 'feature_short_description')->first();
            if ($feature_short_description == null) {
                $feature_short_description = new DataSetting();
            }

            $feature_short_description->key = 'feature_short_description';
            $feature_short_description->type = 'admin_landing_page';
            $feature_short_description->value = $request->feature_short_description[array_search('default', $request->lang)];
            $feature_short_description->save();


            $data = [];
            $default_lang = str_replace('_', '-', app()->getLocale());
            foreach ($request->lang as $index => $key) {
                if ($default_lang == $key && !($request->feature_title[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $feature_title->id,
                                'locale' => $key,
                                'key' => 'feature_title'
                            ],
                            ['value' => $feature_title?->getRawOriginal('value')]
                        );
                    }
                } else {
                    if ($request->feature_title[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $feature_title->id,
                                'locale' => $key,
                                'key' => 'feature_title'
                            ],
                            ['value' => $request->feature_title[$index]]
                        );
                    }
                }
                if ($default_lang == $key && !($request->feature_short_description[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $feature_short_description->id,
                                'locale' => $key,
                                'key' => 'feature_short_description'
                            ],
                            ['value' => $feature_short_description->getRawOriginal('value')]
                        );
                    }
                } else {
                    if ($request->feature_short_description[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $feature_short_description->id,
                                'locale' => $key,
                                'key' => 'feature_short_description'
                            ],
                            ['value' => $request->feature_short_description[$index]]
                        );
                    }
                }
            }

            Toastr::success(translate('messages.feature_section_updated'));
        } elseif ($tab == 'earning-title') {
            $earning_title = DataSetting::where('type', 'admin_landing_page')->where('key', 'earning_title')->first();
            if ($earning_title == null) {
                $earning_title = new DataSetting();
            }

            $earning_title->key = 'earning_title';
            $earning_title->type = 'admin_landing_page';
            $earning_title->value = $request->earning_title[array_search('default', $request->lang)];
            $earning_title->save();

            $earning_sub_title = DataSetting::where('type', 'admin_landing_page')->where('key', 'earning_sub_title')->first();
            if ($earning_sub_title == null) {
                $earning_sub_title = new DataSetting();
            }

            $earning_sub_title->key = 'earning_sub_title';
            $earning_sub_title->type = 'admin_landing_page';
            $earning_sub_title->value = $request->earning_sub_title[array_search('default', $request->lang)];
            $earning_sub_title->save();


            $data = [];
            $default_lang = str_replace('_', '-', app()->getLocale());
            foreach ($request->lang as $index => $key) {
                if ($default_lang == $key && !($request->earning_title[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $earning_title->id,
                                'locale' => $key,
                                'key' => 'earning_title'
                            ],
                            ['value' => $earning_title?->getRawOriginal('value')]
                        );
                    }
                } else {
                    if ($request->earning_title[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $earning_title->id,
                                'locale' => $key,
                                'key' => 'earning_title'
                            ],
                            ['value' => $request->earning_title[$index]]
                        );
                    }
                }
                if ($default_lang == $key && !($request->earning_sub_title[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $earning_sub_title->id,
                                'locale' => $key,
                                'key' => 'earning_sub_title'
                            ],
                            ['value' => $earning_sub_title?->getRawOriginal('value')]
                        );
                    }
                } else {
                    if ($request->earning_sub_title[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $earning_sub_title->id,
                                'locale' => $key,
                                'key' => 'earning_sub_title'
                            ],
                            ['value' => $request->earning_sub_title[$index]]
                        );
                    }
                }
            }

            Toastr::success(translate('messages.earning_section_updated'));
        } elseif ($tab == 'earning-seller-link') {
            $earning_seller_image = DataSetting::where('type', 'admin_landing_page')->where('key', 'earning_seller_image')->first();
            if ($earning_seller_image == null) {
                $earning_seller_image = new DataSetting();
            }
            $earning_seller_image->key = 'earning_seller_image';
            $earning_seller_image->type = 'admin_landing_page';
            $earning_seller_image->value = $request->has('earning_seller_image') ? Helpers::update('earning/', $earning_seller_image->value, 'png', $request->file('earning_seller_image')) : $earning_seller_image->value;
            $earning_seller_image->save();
            DB::table('data_settings')->updateOrInsert(['key' => 'seller_app_earning_links', 'type' => 'admin_landing_page'], [
                'value' => json_encode([
                    'playstore_url_status' => $request['playstore_url_status'],
                    'playstore_url' => $request['playstore_url'],
                    'apple_store_url_status' => $request['apple_store_url_status'],
                    'apple_store_url' => $request['apple_store_url']
                ])
            ]);
            Toastr::success(translate('messages.seller_links_updated'));
        } elseif ($tab == 'earning-dm-link') {
            $earning_delivery_image = DataSetting::where('type', 'admin_landing_page')->where('key', 'earning_delivery_image')->first();
            if ($earning_delivery_image == null) {
                $earning_delivery_image = new DataSetting();
            }
            $earning_delivery_image->key = 'earning_delivery_image';
            $earning_delivery_image->type = 'admin_landing_page';
            $earning_delivery_image->value = $request->has('earning_delivery_image') ? Helpers::update('earning/', $earning_delivery_image->value, 'png', $request->file('earning_delivery_image')) : $earning_delivery_image->value;
            $earning_delivery_image->save();
            DB::table('data_settings')->updateOrInsert(['key' => 'dm_app_earning_links', 'type' => 'admin_landing_page'], [
                'value' => json_encode([
                    'playstore_url_status' => $request['playstore_url_status'],
                    'playstore_url' => $request['playstore_url'],
                    'apple_store_url_status' => $request['apple_store_url_status'],
                    'apple_store_url' => $request['apple_store_url']
                ])
            ]);
            Toastr::success(translate('messages.delivery_man_links_updated'));
        } elseif ($tab == 'why-choose-title') {
            $why_choose_title = DataSetting::where('type', 'admin_landing_page')->where('key', 'why_choose_title')->first();
            if ($why_choose_title == null) {
                $why_choose_title = new DataSetting();
            }

            $why_choose_title->key = 'why_choose_title';
            $why_choose_title->type = 'admin_landing_page';
            $why_choose_title->value = $request->why_choose_title[array_search('default', $request->lang)];
            $why_choose_title->save();

            $data = [];
            $default_lang = str_replace('_', '-', app()->getLocale());
            foreach ($request->lang as $index => $key) {
                if ($default_lang == $key && !($request->why_choose_title[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $why_choose_title->id,
                                'locale' => $key,
                                'key' => 'why_choose_title'
                            ],
                            ['value' => $why_choose_title?->getRawOriginal('value')]
                        );
                    }
                } else {
                    if ($request->why_choose_title[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $why_choose_title->id,
                                'locale' => $key,
                                'key' => 'why_choose_title'
                            ],
                            ['value' => $request->why_choose_title[$index]]
                        );
                    }
                }
            }

            Toastr::success(translate('messages.why_choose_section_updated'));
        } elseif ($tab == 'special-criteria-list') {
            $request->validate([
                'title' => 'required',
                'image' => 'required',
            ]);
            if($request->title[array_search('default', $request->lang)] == ''){
                Toastr::error(translate('default_data_is_required'));
                return back();
            }
            $criteria = new AdminSpecialCriteria();
            $criteria->title = $request->title[array_search('default', $request->lang)];
            $criteria->image = Helpers::upload('special_criteria/', 'png', $request->file('image'));
            $criteria->save();
            $default_lang = str_replace('_', '-', app()->getLocale());
            $data = [];
            foreach ($request->lang as $index => $key) {
                if ($default_lang == $key && !($request->title[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type'  => 'App\Models\AdminSpecialCriteria',
                                'translationable_id'    => $criteria->id,
                                'locale'                => $key,
                                'key'                   => 'title'
                            ],
                            ['value'                 => $criteria->title]
                        );
                    }
                } else {

                    if ($request->title[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type'  => 'App\Models\AdminSpecialCriteria',
                                'translationable_id'    => $criteria->id,
                                'locale'                => $key,
                                'key'                   => 'title'
                            ],
                            ['value'                 => $request->title[$index]]
                        );
                    }
                }
            }

            Toastr::success(translate('messages.criteria_added_successfully'));
        } elseif ($tab == 'download-app-section') {
            $download_user_app_title = DataSetting::where('type', 'admin_landing_page')->where('key', 'download_user_app_title')->first();
            if ($download_user_app_title == null) {
                $download_user_app_title = new DataSetting();
            }

            $download_user_app_title->key = 'download_user_app_title';
            $download_user_app_title->type = 'admin_landing_page';
            $download_user_app_title->value = $request->download_user_app_title[array_search('default', $request->lang)];
            $download_user_app_title->save();

            $download_user_app_sub_title = DataSetting::where('type', 'admin_landing_page')->where('key', 'download_user_app_sub_title')->first();
            if ($download_user_app_sub_title == null) {
                $download_user_app_sub_title = new DataSetting();
            }

            $download_user_app_sub_title->key = 'download_user_app_sub_title';
            $download_user_app_sub_title->type = 'admin_landing_page';
            $download_user_app_sub_title->value = $request->download_user_app_sub_title[array_search('default', $request->lang)];
            $download_user_app_sub_title->save();

            $download_user_app_image = DataSetting::where('type', 'admin_landing_page')->where('key', 'download_user_app_image')->first();
            if ($download_user_app_image == null) {
                $download_user_app_image = new DataSetting();
            }
            $download_user_app_image->key = 'download_user_app_image';
            $download_user_app_image->type = 'admin_landing_page';
            $download_user_app_image->value = $request->has('image') ? Helpers::update('download_user_app_image/', $download_user_app_image->value, 'png', $request->file('image')) : $download_user_app_image->value;
            $download_user_app_image->save();

            $data = [];
            $default_lang = str_replace('_', '-', app()->getLocale());
            foreach ($request->lang as $index => $key) {
                if ($default_lang == $key && !($request->download_user_app_title[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $download_user_app_title->id,
                                'locale' => $key,
                                'key' => 'download_user_app_title'
                            ],
                            ['value' => $download_user_app_title?->getRawOriginal('value')]
                        );
                    }
                } else {
                    if ($request->download_user_app_title[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $download_user_app_title->id,
                                'locale' => $key,
                                'key' => 'download_user_app_title'
                            ],
                            ['value' => $request->download_user_app_title[$index]]
                        );
                    }
                }
                if ($default_lang == $key && !($request->download_user_app_sub_title[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $download_user_app_sub_title->id,
                                'locale' => $key,
                                'key' => 'download_user_app_sub_title'
                            ],
                            ['value' => $download_user_app_sub_title?->getRawOriginal('value')]
                        );
                    }
                } else {
                    if ($request->download_user_app_sub_title[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $download_user_app_sub_title->id,
                                'locale' => $key,
                                'key' => 'download_user_app_sub_title'
                            ],
                            ['value' => $request->download_user_app_sub_title[$index]]
                        );
                    }
                }
            }

            DB::table('data_settings')->updateOrInsert(['key' => 'download_user_app_links', 'type' => 'admin_landing_page'], [
                'value' => json_encode([
                    'playstore_url_status' => $request['playstore_url_status'],
                    'playstore_url' => $request['playstore_url'],
                    'apple_store_url_status' => $request['apple_store_url_status'],
                    'apple_store_url' => $request['apple_store_url']
                ])
            ]);


            Toastr::success(translate('messages.download_app_section_updated'));
        } else if ($tab == 'download-counter-section') {
            DB::table('data_settings')->updateOrInsert(['key' => 'counter_section', 'type' => 'admin_landing_page'], [
                'value' => json_encode([
                    'app_download_count_numbers' => $request['app_download_count_numbers'],
                    'seller_count_numbers' => $request['seller_count_numbers'],
                    'deliveryman_count_numbers' => $request['deliveryman_count_numbers'],
                    'customer_count_numbers' => $request['customer_count_numbers'],
                    'status' => $request['status'],
                ])
            ]);

            Toastr::success(translate('messages.landing_page_counter_section_updated'));
        } elseif ($tab == 'testimonial-title') {
            $testimonial_title = DataSetting::where('type', 'admin_landing_page')->where('key', 'testimonial_title')->first();
            if ($testimonial_title == null) {
                $testimonial_title = new DataSetting();
            }

            $testimonial_title->key = 'testimonial_title';
            $testimonial_title->type = 'admin_landing_page';
            $testimonial_title->value = $request->testimonial_title[array_search('default', $request->lang)];
            $testimonial_title->save();

            $data = [];
            $default_lang = str_replace('_', '-', app()->getLocale());
            foreach ($request->lang as $index => $key) {
                if ($default_lang == $key && !($request->testimonial_title[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $testimonial_title->id,
                                'locale' => $key,
                                'key' => 'testimonial_title'
                            ],
                            ['value' => $testimonial_title?->getRawOriginal('value')]
                        );
                    }
                } else {
                    if ($request->testimonial_title[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $testimonial_title->id,
                                'locale' => $key,
                                'key' => 'testimonial_title'
                            ],
                            ['value' => $request->testimonial_title[$index]]
                        );
                    }
                }
            }

            Toastr::success(translate('messages.testimonial_section_updated'));
        } elseif ($tab == 'testimonial-list') {
            $request->validate([
                'name' => 'required',
                'designation' => 'required',
                'review' => 'required',
                'reviewer_image' => 'required',
                'company_image' => 'required',
            ]);

            $testimonial = new AdminTestimonial();
            $testimonial->name = $request->name;
            $testimonial->designation = $request->designation;
            $testimonial->review = $request->review;
            $testimonial->reviewer_image = Helpers::upload('reviewer_image/', 'png', $request->file('reviewer_image'));
            $testimonial->company_image = Helpers::upload('reviewer_company_image/', 'png', $request->file('company_image'));
            $testimonial->save();
            Toastr::success(translate('messages.testimonial_added_successfully'));
        } elseif ($tab == 'contact-us-section') {
            $contact_us_title = DataSetting::where('type', 'admin_landing_page')->where('key', 'contact_us_title')->first();
            if ($contact_us_title == null) {
                $contact_us_title = new DataSetting();
            }

            $contact_us_title->key = 'contact_us_title';
            $contact_us_title->type = 'admin_landing_page';
            $contact_us_title->value = $request->contact_us_title[array_search('default', $request->lang)];
            $contact_us_title->save();

            $contact_us_sub_title = DataSetting::where('type', 'admin_landing_page')->where('key', 'contact_us_sub_title')->first();
            if ($contact_us_sub_title == null) {
                $contact_us_sub_title = new DataSetting();
            }

            $contact_us_sub_title->key = 'contact_us_sub_title';
            $contact_us_sub_title->type = 'admin_landing_page';
            $contact_us_sub_title->value = $request->contact_us_sub_title[array_search('default', $request->lang)];
            $contact_us_sub_title->save();

            $contact_us_image = DataSetting::where('type', 'admin_landing_page')->where('key', 'contact_us_image')->first();
            if ($contact_us_image == null) {
                $contact_us_image = new DataSetting();
            }
            $contact_us_image->key = 'contact_us_image';
            $contact_us_image->type = 'admin_landing_page';
            $contact_us_image->value = $request->has('image') ? Helpers::update('contact_us_image/', $contact_us_image->value, 'png', $request->file('image')) : $contact_us_image->value;
            $contact_us_image->save();

            $data = [];
            $default_lang = str_replace('_', '-', app()->getLocale());
            foreach ($request->lang as $index => $key) {
                if ($default_lang == $key && !($request->contact_us_title[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $contact_us_title->id,
                                'locale' => $key,
                                'key' => 'contact_us_title'
                            ],
                            ['value' => $contact_us_title?->getRawOriginal('value')]
                        );
                    }
                } else {
                    if ($request->contact_us_title[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $contact_us_title->id,
                                'locale' => $key,
                                'key' => 'contact_us_title'
                            ],
                            ['value' => $request->contact_us_title[$index]]
                        );
                    }
                }
                if ($default_lang == $key && !($request->contact_us_sub_title[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $contact_us_sub_title->id,
                                'locale' => $key,
                                'key' => 'contact_us_sub_title'
                            ],
                            ['value' => $contact_us_sub_title?->getRawOriginal('value')]
                        );
                    }
                } else {
                    if ($request->contact_us_sub_title[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $contact_us_sub_title->id,
                                'locale' => $key,
                                'key' => 'contact_us_sub_title'
                            ],
                            ['value' => $request->contact_us_sub_title[$index]]
                        );
                    }
                }
            }

            DB::table('business_settings')->updateOrInsert(['key' => 'opening_time'], [
                'value' => $request['opening_time']
            ]);

            DB::table('business_settings')->updateOrInsert(['key' => 'closing_time'], [
                'value' => $request['closing_time']
            ]);

            if ($request->opening_day == $request->closing_day) {
                Toastr::error(translate('messages.the_start_day_and_end_day_is_same'));
            } else {
                DB::table('business_settings')->updateOrInsert(['key' => 'opening_day'], [
                    'value' => $request['opening_day']
                ]);

                DB::table('business_settings')->updateOrInsert(['key' => 'closing_day'], [
                    'value' => $request['closing_day']
                ]);
            }


            Toastr::success(translate('messages.contact_section_updated'));
        }elseif ($tab == 'background-color') {
            DB::table('business_settings')->updateOrInsert(['key' => 'backgroundChange'], [
                'value' => json_encode([
                    'primary_1_hex' => $request['header-bg'],
                    'primary_1_rgb' => Helpers::hex_to_rbg($request['header-bg']),
                    'primary_2_hex' => $request['footer-bg'],
                    'primary_2_rgb' => Helpers::hex_to_rbg($request['footer-bg']),
                ])
            ]);
            Toastr::success(translate('messages.background_updated'));
        }
        return back();
    }

    public function promotional_status(Request $request)
    {
        if (env('APP_MODE') == 'demo' && $request->id == 1) {
            Toastr::warning('Sorry!You can not inactive this banner!');
            return back();
        }
        $banner = AdminPromotionalBanner::findOrFail($request->id);
        $banner->status = $request->status;
        $banner->save();
        Toastr::success(translate('messages.banner_status_updated'));
        return back();
    }

    public function promotional_edit($id)
    {
        $banner = AdminPromotionalBanner::withoutGlobalScope('translate')->findOrFail($id);
        return view('admin-views.business-settings.landing-page-settings.admin-promotional-section-edit', compact('banner'));
    }
    public function promotional_update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|max:100',
            'sub_title' => 'required'
        ]);

        if($request->title[array_search('default', $request->lang)] == ''){
            Toastr::error(translate('default_data_is_required'));
            return back();
        }
        $banner = AdminPromotionalBanner::find($id);
        $banner->title = $request->title[array_search('default', $request->lang)];
        $banner->sub_title = $request->sub_title[array_search('default', $request->lang)];
        $banner->image = $request->has('image') ? Helpers::update('promotional_banner/', $banner->image, 'png', $request->file('image')) : $banner->image;
        $banner->save();
        $default_lang = str_replace('_', '-', app()->getLocale());
        foreach ($request->lang as $index => $key) {
            if ($default_lang == $key && !($request->title[$index])) {
                if ($key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type'  => 'App\Models\AdminPromotionalBanner',
                            'translationable_id'    => $banner->id,
                            'locale'                => $key,
                            'key'                   => 'title'
                        ],
                        ['value'                 => $banner->title]
                    );
                }
            } else {

                if ($request->title[$index] && $key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type'  => 'App\Models\AdminPromotionalBanner',
                            'translationable_id'    => $banner->id,
                            'locale'                => $key,
                            'key'                   => 'title'
                        ],
                        ['value'                 => $request->title[$index]]
                    );
                }
            }
            if ($default_lang == $key && !($request->sub_title[$index])) {
                if ($key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type'  => 'App\Models\AdminPromotionalBanner',
                            'translationable_id'    => $banner->id,
                            'locale'                => $key,
                            'key'                   => 'sub_title'
                        ],
                        ['value'                 => $banner->sub_title]
                    );
                }
            } else {

                if ($request->sub_title[$index] && $key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type'  => 'App\Models\AdminPromotionalBanner',
                            'translationable_id'    => $banner->id,
                            'locale'                => $key,
                            'key'                   => 'sub_title'
                        ],
                        ['value'                 => $request->sub_title[$index]]
                    );
                }
            }
        }
        Toastr::success(translate('messages.banner_updated_successfully'));
        return back();
    }

    public function promotional_destroy(AdminPromotionalBanner $banner)
    {
        if (env('APP_MODE') == 'demo' && $banner->id == 1) {
            Toastr::warning(translate('messages.you_can_not_delete_this_banner_please_add_a_new_banner_to_delete'));
            return back();
        }
        $banner->delete();
        Toastr::success(translate('messages.banner_deleted_successfully'));
        return back();
    }

    public function feature_status(Request $request)
    {
        if (env('APP_MODE') == 'demo' && $request->id == 1) {
            Toastr::warning('Sorry!You can not inactive this feature!');
            return back();
        }
        $feature = AdminFeature::findOrFail($request->id);
        $feature->status = $request->status;
        $feature->save();
        Toastr::success(translate('messages.feature_status_updated'));
        return back();
    }

    public function feature_edit($id)
    {
        $feature = AdminFeature::withoutGlobalScope('translate')->findOrFail($id);
        return view('admin-views.business-settings.landing-page-settings.admin-feature-list-edit', compact('feature'));
    }
    public function feature_update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|max:100',
            'sub_title' => 'required'
        ]);

        if($request->title[array_search('default', $request->lang)] == ''){
            Toastr::error(translate('default_data_is_required'));
            return back();
        }
        $feature = AdminFeature::find($id);
        $feature->title = $request->title[array_search('default', $request->lang)];
        $feature->sub_title = $request->sub_title[array_search('default', $request->lang)];
        $feature->image = $request->has('image') ? Helpers::update('admin_feature/', $feature->image, 'png', $request->file('image')) : $feature->image;
        $feature->save();
        $default_lang = str_replace('_', '-', app()->getLocale());
        foreach ($request->lang as $index => $key) {
            if ($default_lang == $key && !($request->title[$index])) {
                if ($key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type'  => 'App\Models\AdminFeature',
                            'translationable_id'    => $feature->id,
                            'locale'                => $key,
                            'key'                   => 'title'
                        ],
                        ['value'                 => $feature->title]
                    );
                }
            } else {

                if ($request->title[$index] && $key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type'  => 'App\Models\AdminFeature',
                            'translationable_id'    => $feature->id,
                            'locale'                => $key,
                            'key'                   => 'title'
                        ],
                        ['value'                 => $request->title[$index]]
                    );
                }
            }
            if ($default_lang == $key && !($request->sub_title[$index])) {
                if ($key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type'  => 'App\Models\AdminFeature',
                            'translationable_id'    => $feature->id,
                            'locale'                => $key,
                            'key'                   => 'sub_title'
                        ],
                        ['value'                 => $feature->sub_title]
                    );
                }
            } else {

                if ($request->sub_title[$index] && $key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type'  => 'App\Models\AdminFeature',
                            'translationable_id'    => $feature->id,
                            'locale'                => $key,
                            'key'                   => 'sub_title'
                        ],
                        ['value'                 => $request->sub_title[$index]]
                    );
                }
            }
        }
        Toastr::success(translate('messages.feature_updated_successfully'));
        return back();
    }

    public function feature_destroy(AdminFeature $feature)
    {
        if (env('APP_MODE') == 'demo' && $feature->id == 1) {
            Toastr::warning(translate('messages.you_can_not_delete_this_feature_please_add_a_new_feature_to_delete'));
            return back();
        }
        $feature->delete();
        Toastr::success(translate('messages.feature_deleted_successfully'));
        return back();
    }

    public function criteria_status(Request $request)
    {
        if (env('APP_MODE') == 'demo' && $request->id == 1) {
            Toastr::warning('Sorry!You can not inactive this criteria!');
            return back();
        }
        $criteria = AdminSpecialCriteria::findOrFail($request->id);
        $criteria->status = $request->status;
        $criteria->save();
        Toastr::success(translate('messages.criteria_status_updated'));
        return back();
    }

    public function criteria_edit($id)
    {
        $criteria = AdminSpecialCriteria::withoutGlobalScope('translate')->findOrFail($id);
        return view('admin-views.business-settings.landing-page-settings.admin-landing-why-choose-edit', compact('criteria'));
    }
    public function criteria_update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|max:100',
        ]);

        if($request->title[array_search('default', $request->lang)] == ''){
            Toastr::error(translate('default_data_is_required'));
            return back();
        }
        $criteria = AdminSpecialCriteria::find($id);
        $criteria->title = $request->title[array_search('default', $request->lang)];
        $criteria->image = $request->has('image') ? Helpers::update('special_criteria/', $criteria->image, 'png', $request->file('image')) : $criteria->image;
        $criteria->save();
        $default_lang = str_replace('_', '-', app()->getLocale());
        foreach ($request->lang as $index => $key) {
            if ($default_lang == $key && !($request->title[$index])) {
                if ($key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type'  => 'App\Models\AdminSpecialCriteria',
                            'translationable_id'    => $criteria->id,
                            'locale'                => $key,
                            'key'                   => 'title'
                        ],
                        ['value'                 => $criteria->title]
                    );
                }
            } else {

                if ($request->title[$index] && $key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type'  => 'App\Models\AdminSpecialCriteria',
                            'translationable_id'    => $criteria->id,
                            'locale'                => $key,
                            'key'                   => 'title'
                        ],
                        ['value'                 => $request->title[$index]]
                    );
                }
            }
        }
        Toastr::success(translate('messages.criteria_updated_successfully'));
        return back();
    }

    public function criteria_destroy(AdminSpecialCriteria $criteria)
    {
        if (env('APP_MODE') == 'demo' && $criteria->id == 1) {
            Toastr::warning(translate('messages.you_can_not_delete_this_criteria_please_add_a_new_criteria_to_delete'));
            return back();
        }
        $criteria->delete();
        Toastr::success(translate('messages.criteria_deleted_successfully'));
        return back();
    }

    public function review_status(Request $request)
    {
        if (env('APP_MODE') == 'demo' && $request->id == 1) {
            Toastr::warning('Sorry!You can not inactive this review!');
            return back();
        }
        $review = AdminTestimonial::findOrFail($request->id);
        $review->status = $request->status;
        $review->save();
        Toastr::success(translate('messages.review_status_updated'));
        return back();
    }

    public function review_edit($id)
    {
        $review = AdminTestimonial::withoutGlobalScope('translate')->findOrFail($id);
        return view('admin-views.business-settings.landing-page-settings.admin-landing-testimonial-test', compact('review'));
    }
    public function review_update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'designation' => 'required',
            'review' => 'required',
        ]);

        $review = AdminTestimonial::findOrFail($id);
        $review->name = $request->name;
        $review->designation = $request->designation;
        $review->review = $request->review;
        $review->reviewer_image = $request->has('reviewer_image') ? Helpers::update('reviewer_image/', $review->reviewer_image, 'png', $request->file('reviewer_image')) : $review->reviewer_image;
        $review->company_image = $request->has('company_image') ? Helpers::update('reviewer_company_image/', $review->company_image, 'png', $request->file('company_image')) : $review->company_image;
        $review->save();

        Toastr::success(translate('messages.review_updated_successfully'));
        return back();
    }

    public function review_destroy(AdminTestimonial $review)
    {
        if (env('APP_MODE') == 'demo' && $review->id == 1) {
            Toastr::warning(translate('messages.you_can_not_delete_this_review_please_add_a_new_review_to_delete'));
            return back();
        }
        $review->delete();
        Toastr::success(translate('messages.review_deleted_successfully'));
        return back();
    }

    public function react_landing_page_settings($tab)
    {
        if ($tab == 'header') {
            return view('admin-views.business-settings.landing-page-settings.react-landing-page-header');
        } else if ($tab == 'company-intro') {
            return view('admin-views.business-settings.landing-page-settings.react-landing-page-company');
        } else if ($tab == 'download-user-app') {
            return view('admin-views.business-settings.landing-page-settings.react-landing-download-apps');
        } else if ($tab == 'promotion-banner') {
            return view('admin-views.business-settings.landing-page-settings.react-landing-promotion-banners');
        } else if ($tab == 'earn-money') {
            return view('admin-views.business-settings.landing-page-settings.react-landing-earn-money');
        } else if ($tab == 'business-section') {
            return view('admin-views.business-settings.landing-page-settings.react-landing-business');
        } else if ($tab == 'testimonials') {
            return view('admin-views.business-settings.landing-page-settings.react-landing-testimonial');
        } else if ($tab == 'fixed-data') {
            return view('admin-views.business-settings.landing-page-settings.react-landing-fixed-data');
        }
    }

    public function update_react_landing_page_settings(Request $request, $tab)
    {
        if (env('APP_MODE') == 'demo') {
            Toastr::info(translate('messages.update_option_is_disable_for_demo'));
            return back();
        }

        if ($tab == 'download-app-section') {
            $download_user_app_title = DataSetting::where('type', 'react_landing_page')->where('key', 'download_user_app_title')->first();
            if ($download_user_app_title == null) {
                $download_user_app_title = new DataSetting();
            }

            $download_user_app_title->key = 'download_user_app_title';
            $download_user_app_title->type = 'react_landing_page';
            $download_user_app_title->value = $request->download_user_app_title[array_search('default', $request->lang)];
            $download_user_app_title->save();

            $download_user_app_sub_title = DataSetting::where('type', 'react_landing_page')->where('key', 'download_user_app_sub_title')->first();
            if ($download_user_app_sub_title == null) {
                $download_user_app_sub_title = new DataSetting();
            }

            $download_user_app_sub_title->key = 'download_user_app_sub_title';
            $download_user_app_sub_title->type = 'react_landing_page';
            $download_user_app_sub_title->value = $request->download_user_app_sub_title[array_search('default', $request->lang)];
            $download_user_app_sub_title->save();

            $download_user_app_image = DataSetting::where('type', 'react_landing_page')->where('key', 'download_user_app_image')->first();
            if ($download_user_app_image == null) {
                $download_user_app_image = new DataSetting();
            }
            $download_user_app_image->key = 'download_user_app_image';
            $download_user_app_image->type = 'react_landing_page';
            $download_user_app_image->value = $request->has('image') ? Helpers::update('download_user_app_image/', $download_user_app_image->value, 'png', $request->file('image')) : $download_user_app_image->value;
            $download_user_app_image->save();

            $data = [];
            $default_lang = str_replace('_', '-', app()->getLocale());
            foreach ($request->lang as $index => $key) {
                if ($default_lang == $key && !($request->download_user_app_title[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $download_user_app_title->id,
                                'locale' => $key,
                                'key' => 'download_user_app_title'
                            ],
                            ['value' => $download_user_app_title?->getRawOriginal('value')]
                        );
                    }
                } else {
                    if ($request->download_user_app_title[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $download_user_app_title->id,
                                'locale' => $key,
                                'key' => 'download_user_app_title'
                            ],
                            ['value' => $request->download_user_app_title[$index]]
                        );
                    }
                }
                if ($default_lang == $key && !($request->download_user_app_sub_title[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $download_user_app_sub_title->id,
                                'locale' => $key,
                                'key' => 'download_user_app_sub_title'
                            ],
                            ['value' => $download_user_app_sub_title?->getRawOriginal('value')]
                        );
                    }
                } else {
                    if ($request->download_user_app_sub_title[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $download_user_app_sub_title->id,
                                'locale' => $key,
                                'key' => 'download_user_app_sub_title'
                            ],
                            ['value' => $request->download_user_app_sub_title[$index]]
                        );
                    }
                }
            }

            DB::table('data_settings')->updateOrInsert(['key' => 'download_user_app_links', 'type' => 'react_landing_page'], [
                'value' => json_encode([
                    'playstore_url_status' => $request['playstore_url_status'],
                    'playstore_url' => $request['playstore_url'],
                    'apple_store_url_status' => $request['apple_store_url_status'],
                    'apple_store_url' => $request['apple_store_url']
                ])
            ]);


            Toastr::success(translate('messages.download_app_section_updated'));
        } elseif ($tab == 'earning-title') {
            $earning_title = DataSetting::where('type', 'react_landing_page')->where('key', 'earning_title')->first();
            if ($earning_title == null) {
                $earning_title = new DataSetting();
            }

            $earning_title->key = 'earning_title';
            $earning_title->type = 'react_landing_page';
            $earning_title->value = $request->earning_title[array_search('default', $request->lang)];
            $earning_title->save();

            $earning_sub_title = DataSetting::where('type', 'react_landing_page')->where('key', 'earning_sub_title')->first();
            if ($earning_sub_title == null) {
                $earning_sub_title = new DataSetting();
            }

            $earning_sub_title->key = 'earning_sub_title';
            $earning_sub_title->type = 'react_landing_page';
            $earning_sub_title->value = $request->earning_sub_title[array_search('default', $request->lang)];
            $earning_sub_title->save();


            $data = [];
            $default_lang = str_replace('_', '-', app()->getLocale());
            foreach ($request->lang as $index => $key) {
                if ($default_lang == $key && !($request->earning_title[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $earning_title->id,
                                'locale' => $key,
                                'key' => 'earning_title'
                            ],
                            ['value' => $earning_title?->getRawOriginal('value')]
                        );
                    }
                } else {
                    if ($request->earning_title[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $earning_title->id,
                                'locale' => $key,
                                'key' => 'earning_title'
                            ],
                            ['value' => $request->earning_title[$index]]
                        );
                    }
                }
                if ($default_lang == $key && !($request->earning_sub_title[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $earning_sub_title->id,
                                'locale' => $key,
                                'key' => 'earning_sub_title'
                            ],
                            ['value' => $earning_sub_title?->getRawOriginal('value')]
                        );
                    }
                } else {
                    if ($request->earning_sub_title[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $earning_sub_title->id,
                                'locale' => $key,
                                'key' => 'earning_sub_title'
                            ],
                            ['value' => $request->earning_sub_title[$index]]
                        );
                    }
                }
            }

            Toastr::success(translate('messages.earning_section_updated'));
        } elseif ($tab == 'earning-seller-link') {
            $earning_seller_title = DataSetting::where('type', 'react_landing_page')->where('key', 'earning_seller_title')->first();
            if ($earning_seller_title == null) {
                $earning_seller_title = new DataSetting();
            }

            $earning_seller_title->key = 'earning_seller_title';
            $earning_seller_title->type = 'react_landing_page';
            $earning_seller_title->value = $request->earning_seller_title[array_search('default', $request->lang)];
            $earning_seller_title->save();

            $earning_seller_sub_title = DataSetting::where('type', 'react_landing_page')->where('key', 'earning_seller_sub_title')->first();
            if ($earning_seller_sub_title == null) {
                $earning_seller_sub_title = new DataSetting();
            }

            $earning_seller_sub_title->key = 'earning_seller_sub_title';
            $earning_seller_sub_title->type = 'react_landing_page';
            $earning_seller_sub_title->value = $request->earning_seller_sub_title[array_search('default', $request->lang)];
            $earning_seller_sub_title->save();

            $earning_seller_button_name = DataSetting::where('type', 'react_landing_page')->where('key', 'earning_seller_button_name')->first();
            if ($earning_seller_button_name == null) {
                $earning_seller_button_name = new DataSetting();
            }

            $earning_seller_button_name->key = 'earning_seller_button_name';
            $earning_seller_button_name->type = 'react_landing_page';
            $earning_seller_button_name->value = $request->earning_seller_button_name[array_search('default', $request->lang)];
            $earning_seller_button_name->save();

            $earning_seller_button_url = DataSetting::where('type', 'react_landing_page')->where('key', 'earning_seller_button_url')->first();
            if ($earning_seller_button_url == null) {
                $earning_seller_button_url = new DataSetting();
            }

            $earning_seller_button_url->key = 'earning_seller_button_url';
            $earning_seller_button_url->type = 'react_landing_page';
            $earning_seller_button_url->value = $request->earning_seller_button_url;
            $earning_seller_button_url->save();


            $data = [];
            $default_lang = str_replace('_', '-', app()->getLocale());
            foreach ($request->lang as $index => $key) {
                if ($default_lang == $key && !($request->earning_seller_title[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $earning_seller_title->id,
                                'locale' => $key,
                                'key' => 'earning_seller_title'
                            ],
                            ['value' => $earning_seller_title?->getRawOriginal('value')]
                        );
                    }
                } else {
                    if ($request->earning_seller_title[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $earning_seller_title->id,
                                'locale' => $key,
                                'key' => 'earning_seller_title'
                            ],
                            ['value' => $request->earning_seller_title[$index]]
                        );
                    }
                }
                if ($default_lang == $key && !($request->earning_seller_sub_title[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $earning_seller_sub_title->id,
                                'locale' => $key,
                                'key' => 'earning_seller_sub_title'
                            ],
                            ['value' => $earning_seller_sub_title?->getRawOriginal('value')]
                        );
                    }
                } else {
                    if ($request->earning_seller_sub_title[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $earning_seller_sub_title->id,
                                'locale' => $key,
                                'key' => 'earning_seller_sub_title'
                            ],
                            ['value' => $request->earning_seller_sub_title[$index]]
                        );
                    }
                }
                if ($default_lang == $key && !($request->earning_seller_button_name[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $earning_seller_button_name->id,
                                'locale' => $key,
                                'key' => 'earning_seller_button_name'
                            ],
                            ['value' => $earning_seller_button_name?->getRawOriginal('value')]
                        );
                    }
                } else {
                    if ($request->earning_seller_button_name[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $earning_seller_button_name->id,
                                'locale' => $key,
                                'key' => 'earning_seller_button_name'
                            ],
                            ['value' => $request->earning_seller_button_name[$index]]
                        );
                    }
                }
            }
            Toastr::success(translate('messages.seller_links_updated'));
        } elseif ($tab == 'earning-dm-link') {
            // dd($request->all());
            $earning_dm_title = DataSetting::where('type', 'react_landing_page')->where('key', 'earning_dm_title')->first();
            if ($earning_dm_title == null) {
                $earning_dm_title = new DataSetting();
            }

            $earning_dm_title->key = 'earning_dm_title';
            $earning_dm_title->type = 'react_landing_page';
            $earning_dm_title->value = $request->earning_dm_title[array_search('default', $request->lang)];
            $earning_dm_title->save();

            $earning_dm_sub_title = DataSetting::where('type', 'react_landing_page')->where('key', 'earning_dm_sub_title')->first();
            if ($earning_dm_sub_title == null) {
                $earning_dm_sub_title = new DataSetting();
            }

            $earning_dm_sub_title->key = 'earning_dm_sub_title';
            $earning_dm_sub_title->type = 'react_landing_page';
            $earning_dm_sub_title->value = $request->earning_dm_sub_title[array_search('default', $request->lang)];
            $earning_dm_sub_title->save();

            $earning_dm_button_name = DataSetting::where('type', 'react_landing_page')->where('key', 'earning_dm_button_name')->first();
            if ($earning_dm_button_name == null) {
                $earning_dm_button_name = new DataSetting();
            }

            $earning_dm_button_name->key = 'earning_dm_button_name';
            $earning_dm_button_name->type = 'react_landing_page';
            $earning_dm_button_name->value = $request->earning_dm_button_name[array_search('default', $request->lang)];
            $earning_dm_button_name->save();

            $earning_dm_button_url = DataSetting::where('type', 'react_landing_page')->where('key', 'earning_dm_button_url')->first();
            if ($earning_dm_button_url == null) {
                $earning_dm_button_url = new DataSetting();
            }

            $earning_dm_button_url->key = 'earning_dm_button_url';
            $earning_dm_button_url->type = 'react_landing_page';
            $earning_dm_button_url->value = $request->earning_dm_button_url;
            $earning_dm_button_url->save();


            $data = [];
            $default_lang = str_replace('_', '-', app()->getLocale());
            foreach ($request->lang as $index => $key) {
                if ($default_lang == $key && !($request->earning_dm_title[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $earning_dm_title->id,
                                'locale' => $key,
                                'key' => 'earning_dm_title'
                            ],
                            ['value' => $earning_dm_title?->getRawOriginal('value')]
                        );
                    }
                } else {
                    if ($request->earning_dm_title[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $earning_dm_title->id,
                                'locale' => $key,
                                'key' => 'earning_dm_title'
                            ],
                            ['value' => $request->earning_dm_title[$index]]
                        );
                    }
                }
                if ($default_lang == $key && !($request->earning_dm_sub_title[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $earning_dm_sub_title->id,
                                'locale' => $key,
                                'key' => 'earning_dm_sub_title'
                            ],
                            ['value' => $earning_dm_sub_title?->getRawOriginal('value')]
                        );
                    }
                } else {
                    if ($request->earning_dm_sub_title[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $earning_dm_sub_title->id,
                                'locale' => $key,
                                'key' => 'earning_dm_sub_title'
                            ],
                            ['value' => $request->earning_dm_sub_title[$index]]
                        );
                    }
                }

                if ($default_lang == $key && !($request->earning_dm_button_name[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $earning_dm_button_name->id,
                                'locale' => $key,
                                'key' => 'earning_dm_button_name'
                            ],
                            ['value' => $earning_dm_button_name?->getRawOriginal('value')]
                        );
                    }
                } else {
                    if ($request->earning_dm_button_name[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $earning_dm_button_name->id,
                                'locale' => $key,
                                'key' => 'earning_dm_button_name'
                            ],
                            ['value' => $request->earning_dm_button_name[$index]]
                        );
                    }
                }
            }
            Toastr::success(translate('messages.delivery_man_links_updated'));
        } elseif ($tab == 'testimonial-title') {
            $testimonial_title = DataSetting::where('type', 'react_landing_page')->where('key', 'testimonial_title')->first();
            if ($testimonial_title == null) {
                $testimonial_title = new DataSetting();
            }

            $testimonial_title->key = 'testimonial_title';
            $testimonial_title->type = 'react_landing_page';
            $testimonial_title->value = $request->testimonial_title[array_search('default', $request->lang)];
            $testimonial_title->save();

            $data = [];
            $default_lang = str_replace('_', '-', app()->getLocale());
            foreach ($request->lang as $index => $key) {
                if ($default_lang == $key && !($request->testimonial_title[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $testimonial_title->id,
                                'locale' => $key,
                                'key' => 'testimonial_title'
                            ],
                            ['value' => $testimonial_title?->getRawOriginal('value')]
                        );
                    }
                } else {
                    if ($request->testimonial_title[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $testimonial_title->id,
                                'locale' => $key,
                                'key' => 'testimonial_title'
                            ],
                            ['value' => $request->testimonial_title[$index]]
                        );
                    }
                }
            }

            Toastr::success(translate('messages.testimonial_section_updated'));
        } elseif ($tab == 'testimonial-list') {
            $request->validate([
                'name' => 'required',
                'designation' => 'required',
                'review' => 'required',
                'reviewer_image' => 'required'
            ]);

            $testimonial = new ReactTestimonial();
            $testimonial->name = $request->name;
            $testimonial->designation = $request->designation;
            $testimonial->review = $request->review;
            $testimonial->reviewer_image = Helpers::upload('reviewer_image/', 'png', $request->file('reviewer_image'));
            $testimonial->company_image = Helpers::upload('reviewer_company_image/', 'png', $request->file('company_image'));
            $testimonial->save();
            Toastr::success(translate('messages.testimonial_added_successfully'));
        } elseif ($tab == 'business-section') {
            $business_title = DataSetting::where('type', 'react_landing_page')->where('key', 'business_title')->first();
            if ($business_title == null) {
                $business_title = new DataSetting();
            }

            $business_title->key = 'business_title';
            $business_title->type = 'react_landing_page';
            $business_title->value = $request->business_title[array_search('default', $request->lang)];
            $business_title->save();

            $business_sub_title = DataSetting::where('type', 'react_landing_page')->where('key', 'business_sub_title')->first();
            if ($business_sub_title == null) {
                $business_sub_title = new DataSetting();
            }

            $business_sub_title->key = 'business_sub_title';
            $business_sub_title->type = 'react_landing_page';
            $business_sub_title->value = $request->business_sub_title[array_search('default', $request->lang)];
            $business_sub_title->save();

            $business_image = DataSetting::where('type', 'react_landing_page')->where('key', 'business_image')->first();
            if ($business_image == null) {
                $business_image = new DataSetting();
            }
            $business_image->key = 'business_image';
            $business_image->type = 'react_landing_page';
            $business_image->value = $request->has('image') ? Helpers::update('business_image/', $business_image->value, 'png', $request->file('image')) : $business_image->value;
            $business_image->save();

            $data = [];
            $default_lang = str_replace('_', '-', app()->getLocale());
            foreach ($request->lang as $index => $key) {
                if ($default_lang == $key && !($request->business_title[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $business_title->id,
                                'locale' => $key,
                                'key' => 'business_title'
                            ],
                            ['value' => $business_title?->getRawOriginal('value')]
                        );
                    }
                } else {
                    if ($request->business_title[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $business_title->id,
                                'locale' => $key,
                                'key' => 'business_title'
                            ],
                            ['value' => $request->business_title[$index]]
                        );
                    }
                }
                if ($default_lang == $key && !($request->business_sub_title[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $business_sub_title->id,
                                'locale' => $key,
                                'key' => 'business_sub_title'
                            ],
                            ['value' => $business_sub_title?->getRawOriginal('value')]
                        );
                    }
                } else {
                    if ($request->business_sub_title[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $business_sub_title->id,
                                'locale' => $key,
                                'key' => 'business_sub_title'
                            ],
                            ['value' => $request->business_sub_title[$index]]
                        );
                    }
                }
            }

            DB::table('data_settings')->updateOrInsert(['key' => 'download_business_app_links', 'type' => 'react_landing_page'], [
                'value' => json_encode([
                    'seller_playstore_url_status' => $request['seller_playstore_url_status'],
                    'seller_playstore_url' => $request['seller_playstore_url'],
                    'seller_appstore_url_status' => $request['seller_appstore_url_status'],
                    'seller_appstore_url' => $request['seller_appstore_url'],
                    'dm_playstore_url_status' => $request['dm_playstore_url_status'],
                    'dm_playstore_url' => $request['dm_playstore_url'],
                    'dm_appstore_url_status' => $request['dm_appstore_url_status'],
                    'dm_appstore_url' => $request['dm_appstore_url'],
                ])
            ]);


            Toastr::success(translate('messages.business_section_updated'));
        } elseif ($tab == 'header-section') {
            $header_title = DataSetting::where('type', 'react_landing_page')->where('key', 'header_title')->first();
            if ($header_title == null) {
                $header_title = new DataSetting();
            }

            $header_title->key = 'header_title';
            $header_title->type = 'react_landing_page';
            $header_title->value = $request->header_title[array_search('default', $request->lang)];
            $header_title->save();

            $header_sub_title = DataSetting::where('type', 'react_landing_page')->where('key', 'header_sub_title')->first();
            if ($header_sub_title == null) {
                $header_sub_title = new DataSetting();
            }

            $header_sub_title->key = 'header_sub_title';
            $header_sub_title->type = 'react_landing_page';
            $header_sub_title->value = $request->header_sub_title[array_search('default', $request->lang)];
            $header_sub_title->save();

            $header_tag_line = DataSetting::where('type', 'react_landing_page')->where('key', 'header_tag_line')->first();
            if ($header_tag_line == null) {
                $header_tag_line = new DataSetting();
            }

            $header_tag_line->key = 'header_tag_line';
            $header_tag_line->type = 'react_landing_page';
            $header_tag_line->value = $request->header_tag_line[array_search('default', $request->lang)];
            $header_tag_line->save();

            $header_icon = DataSetting::where('type', 'react_landing_page')->where('key', 'header_icon')->first();
            if ($header_icon == null) {
                $header_icon = new DataSetting();
            }
            $header_icon->key = 'header_icon';
            $header_icon->type = 'react_landing_page';
            $header_icon->value = $request->has('image') ? Helpers::update('header_icon/', $header_icon->value, 'png', $request->file('image')) : $header_icon->value;
            $header_icon->save();

            $header_banner = DataSetting::where('type', 'react_landing_page')->where('key', 'header_banner')->first();
            if ($header_banner == null) {
                $header_banner = new DataSetting();
            }
            $header_banner->key = 'header_banner';
            $header_banner->type = 'react_landing_page';
            $header_banner->value = $request->has('banner_image') ? Helpers::update('header_banner/', $header_banner->value, 'png', $request->file('banner_image')) : $header_banner->value;
            $header_banner->save();

            $default_lang = str_replace('_', '-', app()->getLocale());
            foreach ($request->lang as $index => $key) {
                if ($default_lang == $key && !($request->header_title[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $header_title->id,
                                'locale' => $key,
                                'key' => 'header_title'
                            ],
                            ['value' => $header_title?->getRawOriginal('value')]
                        );
                    }
                } else {
                    if ($request->header_title[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $header_title->id,
                                'locale' => $key,
                                'key' => 'header_title'
                            ],
                            ['value' => $request->header_title[$index]]
                        );
                    }
                }
                if ($default_lang == $key && !($request->header_sub_title[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $header_sub_title->id,
                                'locale' => $key,
                                'key' => 'header_sub_title'
                            ],
                            ['value' => $header_sub_title?->getRawOriginal('value')]
                        );
                    }
                } else {
                    if ($request->header_sub_title[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $header_sub_title->id,
                                'locale' => $key,
                                'key' => 'header_sub_title'
                            ],
                            ['value' => $request->header_sub_title[$index]]
                        );
                    }
                }
                if ($default_lang == $key && !($request->header_tag_line[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $header_tag_line->id,
                                'locale' => $key,
                                'key' => 'header_tag_line'
                            ],
                            ['value' => $header_tag_line->getRawOriginal('value')]
                        );
                    }
                } else {
                    if ($request->header_tag_line[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $header_tag_line->id,
                                'locale' => $key,
                                'key' => 'header_tag_line'
                            ],
                            ['value' => $request->header_tag_line[$index]]
                        );
                    }
                }
            }

            Toastr::success(translate('messages.header_section_updated'));
        } elseif ($tab == 'company-section') {
            $company_title = DataSetting::where('type', 'react_landing_page')->where('key', 'company_title')->first();
            if ($company_title == null) {
                $company_title = new DataSetting();
            }

            $company_title->key = 'company_title';
            $company_title->type = 'react_landing_page';
            $company_title->value = $request->company_title[array_search('default', $request->lang)];
            $company_title->save();

            $company_sub_title = DataSetting::where('type', 'react_landing_page')->where('key', 'company_sub_title')->first();
            if ($company_sub_title == null) {
                $company_sub_title = new DataSetting();
            }

            $company_sub_title->key = 'company_sub_title';
            $company_sub_title->type = 'react_landing_page';
            $company_sub_title->value = $request->company_sub_title[array_search('default', $request->lang)];
            $company_sub_title->save();

            $company_description = DataSetting::where('type', 'react_landing_page')->where('key', 'company_description')->first();
            if ($company_description == null) {
                $company_description = new DataSetting();
            }

            $company_description->key = 'company_description';
            $company_description->type = 'react_landing_page';
            $company_description->value = $request->company_description[array_search('default', $request->lang)];
            $company_description->save();

            $company_button_name = DataSetting::where('type', 'react_landing_page')->where('key', 'company_button_name')->first();
            if ($company_button_name == null) {
                $company_button_name = new DataSetting();
            }

            $company_button_name->key = 'company_button_name';
            $company_button_name->type = 'react_landing_page';
            $company_button_name->value = $request->company_button_name[array_search('default', $request->lang)];
            $company_button_name->save();

            $company_button_url = DataSetting::where('type', 'react_landing_page')->where('key', 'company_button_url')->first();
            if ($company_button_url == null) {
                $company_button_url = new DataSetting();
            }

            $company_button_url->key = 'company_button_url';
            $company_button_url->type = 'react_landing_page';
            $company_button_url->value = $request->company_button_url;
            $company_button_url->save();

            $default_lang = str_replace('_', '-', app()->getLocale());
            foreach ($request->lang as $index => $key) {
                if ($default_lang == $key && !($request->company_title[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $company_title->id,
                                'locale' => $key,
                                'key' => 'company_title'
                            ],
                            ['value' => $company_title?->getRawOriginal('value')]
                        );
                    }
                } else {
                    if ($request->company_title[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $company_title->id,
                                'locale' => $key,
                                'key' => 'company_title'
                            ],
                            ['value' => $request->company_title[$index]]
                        );
                    }
                }
                if ($default_lang == $key && !($request->company_sub_title[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $company_sub_title->id,
                                'locale' => $key,
                                'key' => 'company_sub_title'
                            ],
                            ['value' => $company_sub_title?->getRawOriginal('value')]
                        );
                    }
                } else {
                    if ($request->company_sub_title[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $company_sub_title->id,
                                'locale' => $key,
                                'key' => 'company_sub_title'
                            ],
                            ['value' => $request->company_sub_title[$index]]
                        );
                    }
                }
                if ($default_lang == $key && !($request->company_description[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $company_description->id,
                                'locale' => $key,
                                'key' => 'company_description'
                            ],
                            ['value' => $company_description->getRawOriginal('value')]
                        );
                    }
                } else {
                    if ($request->company_description[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $company_description->id,
                                'locale' => $key,
                                'key' => 'company_description'
                            ],
                            ['value' => $request->company_description[$index]]
                        );
                    }
                }
                if ($default_lang == $key && !($request->company_button_name[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $company_button_name->id,
                                'locale' => $key,
                                'key' => 'company_button_name'
                            ],
                            ['value' => $company_button_name->getRawOriginal('value')]
                        );
                    }
                } else {
                    if ($request->company_button_name[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $company_button_name->id,
                                'locale' => $key,
                                'key' => 'company_button_name'
                            ],
                            ['value' => $request->company_button_name[$index]]
                        );
                    }
                }
            }

            Toastr::success(translate('messages.company_section_updated'));

        } else if ($tab == 'promotion-banner') {
            $data = [];
            $imageName = null;
            $promotion_banner = DataSetting::where('type', 'react_landing_page')->where('key', 'promotion_banner')->first();
            if ($promotion_banner) {
                $data = json_decode($promotion_banner->value, true);
            }
            if (count($data) >= 6) {
                Toastr::error(translate('messages.you_have_already_added_maximum_banner_image'));
                return back();
            }
            if ($request->has('image')) {
                $imageName = Helpers::upload('promotional_banner/', 'png', $request->file('image'));
            }
            array_push($data, [
                'img' => $imageName,
                // 'title' => $request->title,
                // 'sub_title' => $request->sub_title,
            ]);

            DB::table('data_settings')->updateOrInsert(['key' => 'promotion_banner','type' => 'react_landing_page'], [
                'value' => json_encode($data),
            ]);
            Toastr::success(translate('messages.landing_page_promotion_banner_updated'));
        } else if ($tab == 'fixed-banner') {
            $fixed_promotional_banner = DataSetting::where('type', 'react_landing_page')->where('key', 'fixed_promotional_banner')->first();
            if ($fixed_promotional_banner == null) {
                $fixed_promotional_banner = new DataSetting();
            }
            $fixed_promotional_banner->key = 'fixed_promotional_banner';
            $fixed_promotional_banner->type = 'react_landing_page';
            $fixed_promotional_banner->value = $request->has('fixed_promotional_banner') ? Helpers::update('promotional_banner/', $fixed_promotional_banner->value, 'png', $request->file('fixed_promotional_banner')) : $fixed_promotional_banner->value;
            $fixed_promotional_banner->save();
            Toastr::success(translate('messages.landing_page_promotion_banner_updated'));
        } else if ($tab == 'fixed-newsletter') {
            $fixed_newsletter_title = DataSetting::where('type', 'react_landing_page')->where('key', 'fixed_newsletter_title')->first();
            if ($fixed_newsletter_title == null) {
                $fixed_newsletter_title = new DataSetting();
            }

            $fixed_newsletter_title->key = 'fixed_newsletter_title';
            $fixed_newsletter_title->type = 'react_landing_page';
            $fixed_newsletter_title->value = $request->fixed_newsletter_title[array_search('default', $request->lang)];
            $fixed_newsletter_title->save();

            $fixed_newsletter_sub_title = DataSetting::where('type', 'react_landing_page')->where('key', 'fixed_newsletter_sub_title')->first();
            if ($fixed_newsletter_sub_title == null) {
                $fixed_newsletter_sub_title = new DataSetting();
            }

            $fixed_newsletter_sub_title->key = 'fixed_newsletter_sub_title';
            $fixed_newsletter_sub_title->type = 'react_landing_page';
            $fixed_newsletter_sub_title->value = $request->fixed_newsletter_sub_title[array_search('default', $request->lang)];
            $fixed_newsletter_sub_title->save();

            $default_lang = str_replace('_', '-', app()->getLocale());
            foreach ($request->lang as $index => $key) {
                if ($default_lang == $key && !($request->fixed_newsletter_title[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $fixed_newsletter_title->id,
                                'locale' => $key,
                                'key' => 'fixed_newsletter_title'
                            ],
                            ['value' => $fixed_newsletter_title?->getRawOriginal('value')]
                        );
                    }
                } else {
                    if ($request->fixed_newsletter_title[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $fixed_newsletter_title->id,
                                'locale' => $key,
                                'key' => 'fixed_newsletter_title'
                            ],
                            ['value' => $request->fixed_newsletter_title[$index]]
                        );
                    }
                }
                if ($default_lang == $key && !($request->fixed_newsletter_sub_title[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $fixed_newsletter_sub_title->id,
                                'locale' => $key,
                                'key' => 'fixed_newsletter_sub_title'
                            ],
                            ['value' => $fixed_newsletter_sub_title?->getRawOriginal('value')]
                        );
                    }
                } else {
                    if ($request->fixed_newsletter_sub_title[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $fixed_newsletter_sub_title->id,
                                'locale' => $key,
                                'key' => 'fixed_newsletter_sub_title'
                            ],
                            ['value' => $request->fixed_newsletter_sub_title[$index]]
                        );
                    }
                }
            }

            Toastr::success(translate('messages.landing_page_newsletter_content_updated'));
        } else if ($tab == 'fixed-footer') {
            $fixed_footer_description = DataSetting::where('type', 'react_landing_page')->where('key', 'fixed_footer_description')->first();
            if ($fixed_footer_description == null) {
                $fixed_footer_description = new DataSetting();
            }

            $fixed_footer_description->key = 'fixed_footer_description';
            $fixed_footer_description->type = 'react_landing_page';
            $fixed_footer_description->value = $request->fixed_footer_description[array_search('default', $request->lang)];
            $fixed_footer_description->save();

            $default_lang = str_replace('_', '-', app()->getLocale());
            foreach ($request->lang as $index => $key) {
                if ($default_lang == $key && !($request->fixed_footer_description[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $fixed_footer_description->id,
                                'locale' => $key,
                                'key' => 'fixed_footer_description'
                            ],
                            ['value' => $fixed_footer_description?->getRawOriginal('value')]
                        );
                    }
                } else {
                    if ($request->fixed_footer_description[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $fixed_footer_description->id,
                                'locale' => $key,
                                'key' => 'fixed_footer_description'
                            ],
                            ['value' => $request->fixed_footer_description[$index]]
                        );
                    }
                }
            }

            Toastr::success(translate('messages.landing_page_footer_content_updated'));
        }
        return back();
    }

    public function delete_react_landing_page_settings($tab, $key)
    {
        if (env('APP_MODE') == 'demo') {
            Toastr::info(translate('messages.update_option_is_disable_for_demo'));
            return back();
        }

        $item = DataSetting::where('type','react_landing_page')->where('key', $tab)->first();
        $data = $item ? json_decode($item->value, true) : null;
        if ($data && array_key_exists($key, $data)) {
            if (isset($data[$key]['img']) && Storage::disk('public')->exists('promotion_banner/' . $data[$key]['img'])) {
                Storage::disk('public')->delete('promotion_banner/' . $data[$key]['img']);
            }
            array_splice($data, $key, 1);

            $item->value = json_encode($data);
            $item->save();
            Toastr::success(translate('messages.' . $tab) . ' ' . translate('messages.deleted'));
            return back();
        }
        Toastr::error(translate('messages.not_found'));
        return back();
    }

    public function review_react_status(Request $request)
    {
        if (env('APP_MODE') == 'demo' && $request->id == 1) {
            Toastr::warning('Sorry!You can not inactive this review!');
            return back();
        }
        $review = ReactTestimonial::findOrFail($request->id);
        $review->status = $request->status;
        $review->save();
        Toastr::success(translate('messages.review_status_updated'));
        return back();
    }

    public function review_react_edit($id)
    {
        $review = ReactTestimonial::withoutGlobalScope('translate')->findOrFail($id);
        return view('admin-views.business-settings.landing-page-settings.react-landing-testimonial-edit', compact('review'));
    }
    public function review_react_update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'designation' => 'required',
            'review' => 'required',
        ]);

        $review = ReactTestimonial::findOrFail($id);
        $review->name = $request->name;
        $review->designation = $request->designation;
        $review->review = $request->review;
        $review->reviewer_image = $request->has('reviewer_image') ? Helpers::update('reviewer_image/', $review->reviewer_image, 'png', $request->file('reviewer_image')) : $review->reviewer_image;
        $review->company_image = $request->has('company_image') ? Helpers::update('reviewer_company_image/', $review->company_image, 'png', $request->file('company_image')) : $review->company_image;
        $review->save();

        Toastr::success(translate('messages.review_updated_successfully'));
        return back();
    }

    public function review_react_destroy(ReactTestimonial $review)
    {
        if (env('APP_MODE') == 'demo' && $review->id == 1) {
            Toastr::warning(translate('messages.you_can_not_delete_this_review_please_add_a_new_review_to_delete'));
            return back();
        }
        $review->delete();
        Toastr::success(translate('messages.review_deleted_successfully'));
        return back();
    }

    public function flutter_landing_page_settings($tab)
    {
        if ($tab == 'fixed-data') {
            return view('admin-views.business-settings.landing-page-settings.flutter-fixed-data');
        } else if ($tab == 'special-criteria') {
            return view('admin-views.business-settings.landing-page-settings.flutter-landing-page-special-criteria');
        } else if ($tab == 'join-as') {
            return view('admin-views.business-settings.landing-page-settings.flutter-landing-page-join-as');
        } else if ($tab == 'download-apps') {
            return view('admin-views.business-settings.landing-page-settings.flutter-download-apps');
        }
    }

    public function update_flutter_landing_page_settings(Request $request, $tab)
    {
        if (env('APP_MODE') == 'demo') {
            Toastr::info(translate('messages.update_option_is_disable_for_demo'));
            return back();
        }

        if ($tab == 'special-criteria-list') {
            $request->validate([
                'title' => 'required',
                'image' => 'required',
            ]);
            if($request->title[array_search('default', $request->lang)] == ''){
                Toastr::error(translate('default_data_is_required'));
                return back();
            }
            $criteria = new FlutterSpecialCriteria();
            $criteria->title = $request->title[array_search('default', $request->lang)];
            $criteria->image = Helpers::upload('special_criteria/', 'png', $request->file('image'));
            $criteria->save();
            $default_lang = str_replace('_', '-', app()->getLocale());
            $data = [];
            foreach ($request->lang as $index => $key) {
                if ($default_lang == $key && !($request->title[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type'  => 'App\Models\FlutterSpecialCriteria',
                                'translationable_id'    => $criteria->id,
                                'locale'                => $key,
                                'key'                   => 'title'
                            ],
                            ['value'                 => $criteria->title]
                        );
                    }
                } else {

                    if ($request->title[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type'  => 'App\Models\FlutterSpecialCriteria',
                                'translationable_id'    => $criteria->id,
                                'locale'                => $key,
                                'key'                   => 'title'
                            ],
                            ['value'                 => $request->title[$index]]
                        );
                    }
                }
            }

            Toastr::success(translate('messages.criteria_added_successfully'));
        } elseif ($tab == 'download-app-section') {
            $download_user_app_title = DataSetting::where('type', 'flutter_landing_page')->where('key', 'download_user_app_title')->first();
            if ($download_user_app_title == null) {
                $download_user_app_title = new DataSetting();
            }

            $download_user_app_title->key = 'download_user_app_title';
            $download_user_app_title->type = 'flutter_landing_page';
            $download_user_app_title->value = $request->download_user_app_title[array_search('default', $request->lang)];
            $download_user_app_title->save();

            $download_user_app_sub_title = DataSetting::where('type', 'flutter_landing_page')->where('key', 'download_user_app_sub_title')->first();
            if ($download_user_app_sub_title == null) {
                $download_user_app_sub_title = new DataSetting();
            }

            $download_user_app_sub_title->key = 'download_user_app_sub_title';
            $download_user_app_sub_title->type = 'flutter_landing_page';
            $download_user_app_sub_title->value = $request->download_user_app_sub_title[array_search('default', $request->lang)];
            $download_user_app_sub_title->save();

            $download_user_app_image = DataSetting::where('type', 'flutter_landing_page')->where('key', 'download_user_app_image')->first();
            if ($download_user_app_image == null) {
                $download_user_app_image = new DataSetting();
            }
            $download_user_app_image->key = 'download_user_app_image';
            $download_user_app_image->type = 'flutter_landing_page';
            $download_user_app_image->value = $request->has('image') ? Helpers::update('download_user_app_image/', $download_user_app_image->value, 'png', $request->file('image')) : $download_user_app_image->value;
            $download_user_app_image->save();

            $data = [];
            $default_lang = str_replace('_', '-', app()->getLocale());
            foreach ($request->lang as $index => $key) {
                if ($default_lang == $key && !($request->download_user_app_title[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $download_user_app_title->id,
                                'locale' => $key,
                                'key' => 'download_user_app_title'
                            ],
                            ['value' => $download_user_app_title?->getRawOriginal('value')]
                        );
                    }
                } else {
                    if ($request->download_user_app_title[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $download_user_app_title->id,
                                'locale' => $key,
                                'key' => 'download_user_app_title'
                            ],
                            ['value' => $request->download_user_app_title[$index]]
                        );
                    }
                }
                if ($default_lang == $key && !($request->download_user_app_sub_title[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $download_user_app_sub_title->id,
                                'locale' => $key,
                                'key' => 'download_user_app_sub_title'
                            ],
                            ['value' => $download_user_app_sub_title?->getRawOriginal('value')]
                        );
                    }
                } else {
                    if ($request->download_user_app_sub_title[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $download_user_app_sub_title->id,
                                'locale' => $key,
                                'key' => 'download_user_app_sub_title'
                            ],
                            ['value' => $request->download_user_app_sub_title[$index]]
                        );
                    }
                }
            }

            DB::table('data_settings')->updateOrInsert(['key' => 'download_user_app_links', 'type' => 'flutter_landing_page'], [
                'value' => json_encode([
                    'playstore_url_status' => $request['playstore_url_status'],
                    'playstore_url' => $request['playstore_url'],
                    'apple_store_url_status' => $request['apple_store_url_status'],
                    'apple_store_url' => $request['apple_store_url']
                ])
            ]);


            Toastr::success(translate('messages.download_app_section_updated'));
        } elseif ($tab == 'fixed-header') {

            $fixed_header_title = DataSetting::where('type', 'flutter_landing_page')->where('key', 'fixed_header_title')->first();
            if ($fixed_header_title == null) {
                $fixed_header_title = new DataSetting();
            }

            $fixed_header_title->key = 'fixed_header_title';
            $fixed_header_title->type = 'flutter_landing_page';
            $fixed_header_title->value = $request->fixed_header_title[array_search('default', $request->lang)];
            $fixed_header_title->save();

            $fixed_header_sub_title = DataSetting::where('type', 'flutter_landing_page')->where('key', 'fixed_header_sub_title')->first();
            if ($fixed_header_sub_title == null) {
                $fixed_header_sub_title = new DataSetting();
            }

            $fixed_header_sub_title->key = 'fixed_header_sub_title';
            $fixed_header_sub_title->type = 'flutter_landing_page';
            $fixed_header_sub_title->value = $request->fixed_header_sub_title[array_search('default', $request->lang)];
            $fixed_header_sub_title->save();

            $fixed_header_image = DataSetting::where('type', 'flutter_landing_page')->where('key', 'fixed_header_image')->first();
            if ($fixed_header_image == null) {
                $fixed_header_image = new DataSetting();
            }
            $fixed_header_image->key = 'fixed_header_image';
            $fixed_header_image->type = 'flutter_landing_page';
            $fixed_header_image->value = $request->has('image') ? Helpers::update('fixed_header_image/', $fixed_header_image->value, 'png', $request->file('image')) : $fixed_header_image->value;
            $fixed_header_image->save();

            $data = [];
            $default_lang = str_replace('_', '-', app()->getLocale());
            foreach ($request->lang as $index => $key) {
                if ($default_lang == $key && !($request->fixed_header_title[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $fixed_header_title->id,
                                'locale' => $key,
                                'key' => 'fixed_header_title'
                            ],
                            ['value' => $fixed_header_title?->getRawOriginal('value')]
                        );
                    }
                } else {
                    if ($request->fixed_header_title[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $fixed_header_title->id,
                                'locale' => $key,
                                'key' => 'fixed_header_title'
                            ],
                            ['value' => $request->fixed_header_title[$index]]
                        );
                    }
                }
                if ($default_lang == $key && !($request->fixed_header_sub_title[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $fixed_header_sub_title->id,
                                'locale' => $key,
                                'key' => 'fixed_header_sub_title'
                            ],
                            ['value' => $fixed_header_sub_title?->getRawOriginal('value')]
                        );
                    }
                } else {
                    if ($request->fixed_header_sub_title[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $fixed_header_sub_title->id,
                                'locale' => $key,
                                'key' => 'fixed_header_sub_title'
                            ],
                            ['value' => $request->fixed_header_sub_title[$index]]
                        );
                    }
                }
            }

            Toastr::success(translate('messages.landing_page_header_updated'));
        } elseif ($tab == 'fixed-location') {

            $fixed_location_title = DataSetting::where('type', 'flutter_landing_page')->where('key', 'fixed_location_title')->first();
            if ($fixed_location_title == null) {
                $fixed_location_title = new DataSetting();
            }

            $fixed_location_title->key = 'fixed_location_title';
            $fixed_location_title->type = 'flutter_landing_page';
            $fixed_location_title->value = $request->fixed_location_title[array_search('default', $request->lang)];
            $fixed_location_title->save();

            $data = [];
            $default_lang = str_replace('_', '-', app()->getLocale());
            foreach ($request->lang as $index => $key) {
                if ($default_lang == $key && !($request->fixed_location_title[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $fixed_location_title->id,
                                'locale' => $key,
                                'key' => 'fixed_location_title'
                            ],
                            ['value' => $fixed_location_title?->getRawOriginal('value')]
                        );
                    }
                } else {
                    if ($request->fixed_location_title[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $fixed_location_title->id,
                                'locale' => $key,
                                'key' => 'fixed_location_title'
                            ],
                            ['value' => $request->fixed_location_title[$index]]
                        );
                    }
                }
            }

            Toastr::success(translate('messages.landing_page_location_title_updated'));
        } elseif ($tab == 'fixed-module') {

            $fixed_module_title = DataSetting::where('type', 'flutter_landing_page')->where('key', 'fixed_module_title')->first();
            if ($fixed_module_title == null) {
                $fixed_module_title = new DataSetting();
            }

            $fixed_module_title->key = 'fixed_module_title';
            $fixed_module_title->type = 'flutter_landing_page';
            $fixed_module_title->value = $request->fixed_module_title[array_search('default', $request->lang)];
            $fixed_module_title->save();

            $fixed_module_sub_title = DataSetting::where('type', 'flutter_landing_page')->where('key', 'fixed_module_sub_title')->first();
            if ($fixed_module_sub_title == null) {
                $fixed_module_sub_title = new DataSetting();
            }

            $fixed_module_sub_title->key = 'fixed_module_sub_title';
            $fixed_module_sub_title->type = 'flutter_landing_page';
            $fixed_module_sub_title->value = $request->fixed_module_sub_title[array_search('default', $request->lang)];
            $fixed_module_sub_title->save();

            $data = [];
            $default_lang = str_replace('_', '-', app()->getLocale());
            foreach ($request->lang as $index => $key) {
                if ($default_lang == $key && !($request->fixed_module_title[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $fixed_module_title->id,
                                'locale' => $key,
                                'key' => 'fixed_module_title'
                            ],
                            ['value' => $fixed_module_title?->getRawOriginal('value')]
                        );
                    }
                } else {
                    if ($request->fixed_module_title[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $fixed_module_title->id,
                                'locale' => $key,
                                'key' => 'fixed_module_title'
                            ],
                            ['value' => $request->fixed_module_title[$index]]
                        );
                    }
                }
                if ($default_lang == $key && !($request->fixed_module_sub_title[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $fixed_module_sub_title->id,
                                'locale' => $key,
                                'key' => 'fixed_module_sub_title'
                            ],
                            ['value' => $fixed_module_sub_title?->getRawOriginal('value')]
                        );
                    }
                } else {
                    if ($request->fixed_module_sub_title[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $fixed_module_sub_title->id,
                                'locale' => $key,
                                'key' => 'fixed_module_sub_title'
                            ],
                            ['value' => $request->fixed_module_sub_title[$index]]
                        );
                    }
                }
            }

            Toastr::success(translate('messages.landing_page_module_updated'));
        } elseif ($tab == 'join-seller') {
            $join_seller_title = DataSetting::where('type', 'flutter_landing_page')->where('key', 'join_seller_title')->first();
            if ($join_seller_title == null) {
                $join_seller_title = new DataSetting();
            }

            $join_seller_title->key = 'join_seller_title';
            $join_seller_title->type = 'flutter_landing_page';
            $join_seller_title->value = $request->join_seller_title[array_search('default', $request->lang)];
            $join_seller_title->save();

            $join_seller_sub_title = DataSetting::where('type', 'flutter_landing_page')->where('key', 'join_seller_sub_title')->first();
            if ($join_seller_sub_title == null) {
                $join_seller_sub_title = new DataSetting();
            }

            $join_seller_sub_title->key = 'join_seller_sub_title';
            $join_seller_sub_title->type = 'flutter_landing_page';
            $join_seller_sub_title->value = $request->join_seller_sub_title[array_search('default', $request->lang)];
            $join_seller_sub_title->save();

            $join_seller_button_name = DataSetting::where('type', 'flutter_landing_page')->where('key', 'join_seller_button_name')->first();
            if ($join_seller_button_name == null) {
                $join_seller_button_name = new DataSetting();
            }

            $join_seller_button_name->key = 'join_seller_button_name';
            $join_seller_button_name->type = 'flutter_landing_page';
            $join_seller_button_name->value = $request->join_seller_button_name[array_search('default', $request->lang)];
            $join_seller_button_name->save();

            $join_seller_button_url = DataSetting::where('type', 'flutter_landing_page')->where('key', 'join_seller_button_url')->first();
            if ($join_seller_button_url == null) {
                $join_seller_button_url = new DataSetting();
            }

            $join_seller_button_url->key = 'join_seller_button_url';
            $join_seller_button_url->type = 'flutter_landing_page';
            $join_seller_button_url->value = $request->join_seller_button_url;
            $join_seller_button_url->save();

            $data = [];
            $default_lang = str_replace('_', '-', app()->getLocale());
            foreach ($request->lang as $index => $key) {
                if ($default_lang == $key && !($request->join_seller_title[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $join_seller_title->id,
                                'locale' => $key,
                                'key' => 'join_seller_title'
                            ],
                            ['value' => $join_seller_title?->getRawOriginal('value')]
                        );
                    }
                } else {
                    if ($request->join_seller_title[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $join_seller_title->id,
                                'locale' => $key,
                                'key' => 'join_seller_title'
                            ],
                            ['value' => $request->join_seller_title[$index]]
                        );
                    }
                }
                if ($default_lang == $key && !($request->join_seller_sub_title[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $join_seller_sub_title->id,
                                'locale' => $key,
                                'key' => 'join_seller_sub_title'
                            ],
                            ['value' => $join_seller_sub_title?->getRawOriginal('value')]
                        );
                    }
                } else {
                    if ($request->join_seller_sub_title[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $join_seller_sub_title->id,
                                'locale' => $key,
                                'key' => 'join_seller_sub_title'
                            ],
                            ['value' => $request->join_seller_sub_title[$index]]
                        );
                    }
                }
                if ($default_lang == $key && !($request->join_seller_button_name[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $join_seller_button_name->id,
                                'locale' => $key,
                                'key' => 'join_seller_button_name'
                            ],
                            ['value' => $join_seller_button_name->getRawOriginal('value')]
                        );
                    }
                } else {
                    if ($request->join_seller_button_name[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $join_seller_button_name->id,
                                'locale' => $key,
                                'key' => 'join_seller_button_name'
                            ],
                            ['value' => $request->join_seller_button_name[$index]]
                        );
                    }
                }
            }

            Toastr::success(translate('messages.join_as_seller_data_updated'));
        } elseif ($tab == 'join-delivery') {
            $join_delivery_man_title = DataSetting::where('type', 'flutter_landing_page')->where('key', 'join_delivery_man_title')->first();
            if ($join_delivery_man_title == null) {
                $join_delivery_man_title = new DataSetting();
            }

            $join_delivery_man_title->key = 'join_delivery_man_title';
            $join_delivery_man_title->type = 'flutter_landing_page';
            $join_delivery_man_title->value = $request->join_delivery_man_title[array_search('default', $request->lang)];
            $join_delivery_man_title->save();

            $join_delivery_man_sub_title = DataSetting::where('type', 'flutter_landing_page')->where('key', 'join_delivery_man_sub_title')->first();
            if ($join_delivery_man_sub_title == null) {
                $join_delivery_man_sub_title = new DataSetting();
            }

            $join_delivery_man_sub_title->key = 'join_delivery_man_sub_title';
            $join_delivery_man_sub_title->type = 'flutter_landing_page';
            $join_delivery_man_sub_title->value = $request->join_delivery_man_sub_title[array_search('default', $request->lang)];
            $join_delivery_man_sub_title->save();

            $join_delivery_man_button_name = DataSetting::where('type', 'flutter_landing_page')->where('key', 'join_delivery_man_button_name')->first();
            if ($join_delivery_man_button_name == null) {
                $join_delivery_man_button_name = new DataSetting();
            }

            $join_delivery_man_button_name->key = 'join_delivery_man_button_name';
            $join_delivery_man_button_name->type = 'flutter_landing_page';
            $join_delivery_man_button_name->value = $request->join_delivery_man_button_name[array_search('default', $request->lang)];
            $join_delivery_man_button_name->save();

            $join_delivery_man_button_url = DataSetting::where('type', 'flutter_landing_page')->where('key', 'join_delivery_man_button_url')->first();
            if ($join_delivery_man_button_url == null) {
                $join_delivery_man_button_url = new DataSetting();
            }

            $join_delivery_man_button_url->key = 'join_delivery_man_button_url';
            $join_delivery_man_button_url->type = 'flutter_landing_page';
            $join_delivery_man_button_url->value = $request->join_delivery_man_button_url;
            $join_delivery_man_button_url->save();

            $data = [];
            $default_lang = str_replace('_', '-', app()->getLocale());
            foreach ($request->lang as $index => $key) {
                if ($default_lang == $key && !($request->join_delivery_man_title[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $join_delivery_man_title->id,
                                'locale' => $key,
                                'key' => 'join_delivery_man_title'
                            ],
                            ['value' => $join_delivery_man_title?->getRawOriginal('value')]
                        );
                    }
                } else {
                    if ($request->join_delivery_man_title[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $join_delivery_man_title->id,
                                'locale' => $key,
                                'key' => 'join_delivery_man_title'
                            ],
                            ['value' => $request->join_delivery_man_title[$index]]
                        );
                    }
                }
                if ($default_lang == $key && !($request->join_delivery_man_sub_title[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $join_delivery_man_sub_title->id,
                                'locale' => $key,
                                'key' => 'join_delivery_man_sub_title'
                            ],
                            ['value' => $join_delivery_man_sub_title?->getRawOriginal('value')]
                        );
                    }
                } else {
                    if ($request->join_delivery_man_sub_title[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $join_delivery_man_sub_title->id,
                                'locale' => $key,
                                'key' => 'join_delivery_man_sub_title'
                            ],
                            ['value' => $request->join_delivery_man_sub_title[$index]]
                        );
                    }
                }
                if ($default_lang == $key && !($request->join_delivery_man_button_name[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $join_delivery_man_button_name->id,
                                'locale' => $key,
                                'key' => 'join_delivery_man_button_name'
                            ],
                            ['value' => $join_delivery_man_button_name->getRawOriginal('value')]
                        );
                    }
                } else {
                    if ($request->join_delivery_man_button_name[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $join_delivery_man_button_name->id,
                                'locale' => $key,
                                'key' => 'join_delivery_man_button_name'
                            ],
                            ['value' => $request->join_delivery_man_button_name[$index]]
                        );
                    }
                }
            }

            Toastr::success(translate('messages.join_as_delivery_man_data_updated'));
        }
        return back();
    }

    public function flutter_criteria_status(Request $request)
    {
        if (env('APP_MODE') == 'demo' && $request->id == 1) {
            Toastr::warning('Sorry!You can not inactive this criteria!');
            return back();
        }
        $criteria = FlutterSpecialCriteria::findOrFail($request->id);
        $criteria->status = $request->status;
        $criteria->save();
        Toastr::success(translate('messages.criteria_status_updated'));
        return back();
    }

    public function flutter_criteria_edit($id)
    {
        $criteria = FlutterSpecialCriteria::withoutGlobalScope('translate')->findOrFail($id);
        return view('admin-views.business-settings.landing-page-settings.flutter-landing-page-special-criteria-edit', compact('criteria'));
    }
    public function flutter_criteria_update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|max:100',
        ]);

        if($request->title[array_search('default', $request->lang)] == ''){
            Toastr::error(translate('default_data_is_required'));
            return back();
        }
        $criteria = FlutterSpecialCriteria::find($id);
        $criteria->title = $request->title[array_search('default', $request->lang)];
        $criteria->image = $request->has('image') ? Helpers::update('special_criteria/', $criteria->image, 'png', $request->file('image')) : $criteria->image;
        $criteria->save();
        $default_lang = str_replace('_', '-', app()->getLocale());
        foreach ($request->lang as $index => $key) {
            if ($default_lang == $key && !($request->title[$index])) {
                if ($key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type'  => 'App\Models\FlutterSpecialCriteria',
                            'translationable_id'    => $criteria->id,
                            'locale'                => $key,
                            'key'                   => 'title'
                        ],
                        ['value'                 => $criteria->title]
                    );
                }
            } else {

                if ($request->title[$index] && $key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type'  => 'App\Models\FlutterSpecialCriteria',
                            'translationable_id'    => $criteria->id,
                            'locale'                => $key,
                            'key'                   => 'title'
                        ],
                        ['value'                 => $request->title[$index]]
                    );
                }
            }
        }
        Toastr::success(translate('messages.criteria_updated_successfully'));
        return back();
    }

    public function flutter_criteria_destroy(FlutterSpecialCriteria $criteria)
    {
        if (env('APP_MODE') == 'demo' && $criteria->id == 1) {
            Toastr::warning(translate('messages.you_can_not_delete_this_criteria_please_add_a_new_criteria_to_delete'));
            return back();
        }
        $criteria->delete();
        Toastr::success(translate('messages.criteria_deleted_successfully'));
        return back();
    }

    public function email_index(Request $request,$type,$tab)
    {
        $template = $request->query('template',null);
        if ($tab == 'new-order') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.place-order-format',compact('template'));
        } else if ($tab == 'forgot-password') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.forgot-pass-format',compact('template'));
        } else if ($tab == 'store-registration') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.store-registration-format',compact('template'));
        } else if ($tab == 'dm-registration') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.dm-registration-format',compact('template'));
        } else if ($tab == 'registration') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.registration-format',compact('template'));
        } else if ($tab == 'approve') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.approve-format',compact('template'));
        } else if ($tab == 'deny') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.deny-format',compact('template'));
        } else if ($tab == 'withdraw-request') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.withdraw-request-format',compact('template'));
        } else if ($tab == 'withdraw-approve') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.withdraw-approve-format',compact('template'));
        } else if ($tab == 'withdraw-deny') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.withdraw-deny-format',compact('template'));
        } else if ($tab == 'campaign-request') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.campaign-request-format',compact('template'));
        } else if ($tab == 'campaign-approve') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.campaign-approve-format',compact('template'));
        } else if ($tab == 'campaign-deny') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.campaign-deny-format',compact('template'));
        } else if ($tab == 'refund-request') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.refund-request-format',compact('template'));
        } else if ($tab == 'login') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.login-format',compact('template'));
        } else if ($tab == 'suspend') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.suspend-format',compact('template'));
        } else if ($tab == 'cash-collect') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.cash-collect-format',compact('template'));
        } else if ($tab == 'registration-otp') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.registration-otp-format',compact('template'));
        } else if ($tab == 'login-otp') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.login-otp-format',compact('template'));
        } else if ($tab == 'order-verification') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.order-verification-format',compact('template'));
        } else if ($tab == 'refund-request-deny') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.refund-request-deny-format',compact('template'));
        } else if ($tab == 'add-fund') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.add-fund-format',compact('template'));
        } else if ($tab == 'refund-order') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.refund-order-format',compact('template'));
        } else if ($tab == 'product-approved') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.product-approved-format',compact('template'));
        } else if ($tab == 'product-deny') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.product-deny-format',compact('template'));
        } else if ($tab == 'offline-payment-approve') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.offline-approved-format',compact('template'));
        } else if ($tab == 'offline-payment-deny') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.offline-deny-format',compact('template'));
        }

    }

    public function update_email_index(Request $request,$type,$tab)
    {
        if (env('APP_MODE') == 'demo') {
            Toastr::info(translate('messages.update_option_is_disable_for_demo'));
            return back();
        }

        if ($tab == 'new-order') {
            $email_type = 'new_order';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'new_order')->first();
        }elseif($tab == 'forget-password'){
            $email_type = 'forget_password';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'forget_password')->first();
        }elseif($tab == 'store-registration'){
            $email_type = 'store_registration';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'store_registration')->first();
        }elseif($tab == 'dm-registration'){
            $email_type = 'dm_registration';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'dm_registration')->first();
        }elseif($tab == 'registration'){
            $email_type = 'registration';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'registration')->first();
        }elseif($tab == 'approve'){
            $email_type = 'approve';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'approve')->first();
        }elseif($tab == 'deny'){
            $email_type = 'deny';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'deny')->first();
        }elseif($tab == 'withdraw-request'){
            $email_type = 'withdraw_request';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'withdraw_request')->first();
        }elseif($tab == 'withdraw-approve'){
            $email_type = 'withdraw_approve';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'withdraw_approve')->first();
        }elseif($tab == 'withdraw-deny'){
            $email_type = 'withdraw_deny';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'withdraw_deny')->first();
        }elseif($tab == 'campaign-request'){
            $email_type = 'campaign_request';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'campaign_request')->first();
        }elseif($tab == 'campaign-approve'){
            $email_type = 'campaign_approve';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'campaign_approve')->first();
        }elseif($tab == 'campaign-deny'){
            $email_type = 'campaign_deny';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'campaign_deny')->first();
        }elseif($tab == 'refund-request'){
            $email_type = 'refund_request';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'refund_request')->first();
        }elseif($tab == 'login'){
            $email_type = 'login';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'login')->first();
        }elseif($tab == 'suspend'){
            $email_type = 'suspend';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'suspend')->first();
        }elseif($tab == 'cash-collect'){
            $email_type = 'cash_collect';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'cash_collect')->first();
        }elseif($tab == 'registration-otp'){
            $email_type = 'registration_otp';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'registration_otp')->first();
        }elseif($tab == 'login-otp'){
            $email_type = 'login_otp';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'login_otp')->first();
        }elseif($tab == 'order-verification'){
            $email_type = 'order_verification';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'order_verification')->first();
        }elseif($tab == 'refund-request-deny'){
            $email_type = 'refund_request_deny';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'refund_request_deny')->first();
        }elseif($tab == 'add-fund'){
            $email_type = 'add_fund';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'add_fund')->first();
        }elseif($tab == 'refund-order'){
            $email_type = 'refund_order';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'refund_order')->first();
        }elseif($tab == 'product-deny'){
            $email_type = 'product_deny';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'product_deny')->first();
        }elseif($tab == 'product-approved'){
            $email_type = 'product_approved';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'product_approved')->first();

        }elseif($tab == 'offline-payment-deny'){
            $email_type = 'offline_payment_deny';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'offline_payment_deny')->first();
        }elseif($tab == 'offline-payment-approve'){
            $email_type = 'offline_payment_approve';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'offline_payment_approve')->first();
        }

        if ($template == null) {
            $template = new EmailTemplate();
        }
        if($request->title[array_search('default', $request->lang)] == ''){
            Toastr::error(translate('default_data_is_required'));
            return back();
        }
        $template->title = $request->title[array_search('default', $request->lang)];
        $template->body = $request->body[array_search('default', $request->lang)];
        $template->button_name = $request->button_name?$request->button_name[array_search('default', $request->lang)]:'';
        $template->footer_text = $request->footer_text[array_search('default', $request->lang)];
        $template->copyright_text = $request->copyright_text[array_search('default', $request->lang)];
        $template->background_image = $request->has('background_image') ? Helpers::update('email_template/', $template->background_image, 'png', $request->file('background_image')) : $template->background_image;
        $template->image = $request->has('image') ? Helpers::update('email_template/', $template->image, 'png', $request->file('image')) : $template->image;
        $template->logo = $request->has('logo') ? Helpers::update('email_template/', $template->logo, 'png', $request->file('logo')) : $template->logo;
        $template->icon = $request->has('icon') ? Helpers::update('email_template/', $template->icon, 'png', $request->file('icon')) : $template->icon;
        $template->email_type = $email_type;
        $template->type = $type;
        $template->button_url = $request->button_url??'';
        $template->email_template = $request->email_template;
        $template->privacy = $request->privacy?'1':0;
        $template->refund = $request->refund?'1':0;
        $template->cancelation = $request->cancelation?'1':0;
        $template->contact = $request->contact?'1':0;
        $template->facebook = $request->facebook?'1':0;
        $template->instagram = $request->instagram?'1':0;
        $template->twitter = $request->twitter?'1':0;
        $template->linkedin = $request->linkedin?'1':0;
        $template->pinterest = $request->pinterest?'1':0;
        $template->save();
        $default_lang = str_replace('_', '-', app()->getLocale());
        foreach ($request->lang as $index => $key) {
            if ($default_lang == $key && !($request->title[$index])) {
                if ($key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type'  => 'App\Models\EmailTemplate',
                            'translationable_id'    => $template->id,
                            'locale'                => $key,
                            'key'                   => 'title'
                        ],
                        ['value'                 => $request->title[array_search('default', $request->lang)]??'']
                    );
                }
            } else {

                if ($request->title[$index] && $key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type'  => 'App\Models\EmailTemplate',
                            'translationable_id'    => $template->id,
                            'locale'                => $key,
                            'key'                   => 'title'
                        ],
                        ['value'                 => $request->title[$index]]
                    );
                }
            }
            if ($default_lang == $key && !($request->body[$index])) {
                if ($key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type'  => 'App\Models\EmailTemplate',
                            'translationable_id'    => $template->id,
                            'locale'                => $key,
                            'key'                   => 'body'
                        ],
                        ['value'                 => $request->body[array_search('default', $request->lang)]??'']
                    );
                }
            } else {
                if ($request->body[$index] && $key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type'  => 'App\Models\EmailTemplate',
                            'translationable_id'    => $template->id,
                            'locale'                => $key,
                            'key'                   => 'body'
                        ],
                        ['value'                 => $request->body[$index]]
                    );
                }
            }
            if ($default_lang == $key && !($request->button_name && $request->button_name[$index])) {
                if ($key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type'  => 'App\Models\EmailTemplate',
                            'translationable_id'    => $template->id,
                            'locale'                => $key,
                            'key'                   => 'button_name'
                        ],
                        ['value'                 => $request->button_name[array_search('default', $request->lang)]??'']
                    );
                }
            } else {

                if ($request->button_name && $request->button_name[$index] && $key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type'  => 'App\Models\EmailTemplate',
                            'translationable_id'    => $template->id,
                            'locale'                => $key,
                            'key'                   => 'button_name'
                        ],
                        ['value'                 => $request->button_name[$index]]
                    );
                }
            }
            if ($default_lang == $key && !($request->footer_text[$index])) {
                if ($key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type'  => 'App\Models\EmailTemplate',
                            'translationable_id'    => $template->id,
                            'locale'                => $key,
                            'key'                   => 'footer_text'
                        ],
                        ['value'                 => $request->footer_text[array_search('default', $request->lang)]??'']
                    );
                }
            } else {

                if ($request->footer_text[$index] && $key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type'  => 'App\Models\EmailTemplate',
                            'translationable_id'    => $template->id,
                            'locale'                => $key,
                            'key'                   => 'footer_text'
                        ],
                        ['value'                 => $request->footer_text[$index]]
                    );
                }
            }
            if ($default_lang == $key && !($request->copyright_text[$index])) {
                if ($key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type'  => 'App\Models\EmailTemplate',
                            'translationable_id'    => $template->id,
                            'locale'                => $key,
                            'key'                   => 'copyright_text'
                        ],
                        ['value'                 => $request->copyright_text[array_search('default', $request->lang)]??'']
                    );
                }
            } else {

                if ($request->copyright_text[$index] && $key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type'  => 'App\Models\EmailTemplate',
                            'translationable_id'    => $template->id,
                            'locale'                => $key,
                            'key'                   => 'copyright_text'
                        ],
                        ['value'                 => $request->copyright_text[$index]]
                    );
                }
            }
        }

        Toastr::success(translate('messages.template_added_successfully'));
        return back();
    }

    public function update_email_status(Request $request,$type,$tab,$status)
    {

        if (env('APP_MODE') == 'demo') {
            Toastr::info(translate('messages.update_option_is_disable_for_demo'));
            return back();
        }

        if ($tab == 'place-order') {
            DB::table('business_settings')->updateOrInsert(['key' => 'place_order_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'forgot-password') {
            DB::table('business_settings')->updateOrInsert(['key' => 'forget_password_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'store-registration') {
            DB::table('business_settings')->updateOrInsert(['key' => 'store_registration_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'dm-registration') {
            DB::table('business_settings')->updateOrInsert(['key' => 'dm_registration_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'registration') {
            DB::table('business_settings')->updateOrInsert(['key' => 'registration_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'approve') {
            DB::table('business_settings')->updateOrInsert(['key' => 'approve_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'deny') {
            DB::table('business_settings')->updateOrInsert(['key' => 'deny_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'withdraw-request') {
            DB::table('business_settings')->updateOrInsert(['key' => 'withdraw_request_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'withdraw-approve') {
            DB::table('business_settings')->updateOrInsert(['key' => 'withdraw_approve_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'withdraw-deny') {
            DB::table('business_settings')->updateOrInsert(['key' => 'withdraw_deny_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'campaign-request') {
            DB::table('business_settings')->updateOrInsert(['key' => 'campaign_request_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'campaign-approve') {
            DB::table('business_settings')->updateOrInsert(['key' => 'campaign_approve_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'campaign-deny') {
            DB::table('business_settings')->updateOrInsert(['key' => 'campaign_deny_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'refund-request') {
            DB::table('business_settings')->updateOrInsert(['key' => 'refund_request_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'login') {
            DB::table('business_settings')->updateOrInsert(['key' => 'login_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'suspend') {
            DB::table('business_settings')->updateOrInsert(['key' => 'suspend_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'cash-collect') {
            DB::table('business_settings')->updateOrInsert(['key' => 'cash_collect_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'registration-otp') {
            DB::table('business_settings')->updateOrInsert(['key' => 'registration_otp_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'login-otp') {
            DB::table('business_settings')->updateOrInsert(['key' => 'login_otp_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'order-verification') {
            DB::table('business_settings')->updateOrInsert(['key' => 'order_verification_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'refund-request-deny') {
            DB::table('business_settings')->updateOrInsert(['key' => 'refund_request_deny_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'add-fund') {
            DB::table('business_settings')->updateOrInsert(['key' => 'add_fund_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'refund-order') {
            DB::table('business_settings')->updateOrInsert(['key' => 'refund_order_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'product-deny') {
            DB::table('business_settings')->updateOrInsert(['key' => 'product_deny_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'product-approved') {
            DB::table('business_settings')->updateOrInsert(['key' => 'product_approve_mail_status_'.$type], [
                'value' => $status
            ]);

        } else if ($tab == 'offline-payment-deny') {
            DB::table('business_settings')->updateOrInsert(['key' => 'offline_payment_deny_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'offline-payment-approve') {
            DB::table('business_settings')->updateOrInsert(['key' => 'offline_payment_approve_mail_status_'.$type], [
                'value' => $status
            ]);
        }

        Toastr::success(translate('messages.email_status_updated'));
        return back();

    }

    public function login_url_page(){
        $data=array_column(DataSetting::whereIn('key',['store_employee_login_url','store_login_url','admin_employee_login_url','admin_login_url'
        ])->get(['key','value'])->toArray(), 'value', 'key');

        return view('admin-views.login-setup.login_setup',compact('data'));
    }
    public function login_url_page_update(Request $request){

        $request->validate([
            'type' => 'required',
            'admin_login_url' => 'nullable|regex:/^[a-zA-Z0-9\-\_]+$/u|unique:data_settings,value',
            'admin_employee_login_url' => 'nullable|regex:/^[a-zA-Z0-9\-\_]+$/u|unique:data_settings,value',
            'store_login_url' => 'nullable|regex:/^[a-zA-Z0-9\-\_]+$/u|unique:data_settings,value',
            'store_employee_login_url' => 'nullable|regex:/^[a-zA-Z0-9\-\_]+$/u|unique:data_settings,value',
        ]);

        if($request->type == 'admin') {
            DataSetting::query()->updateOrInsert(['key' => 'admin_login_url','type' => 'login_admin'], [
                'value' => $request->admin_login_url
            ]);
        }
        elseif($request->type == 'admin_employee') {
            DataSetting::query()->updateOrInsert(['key' => 'admin_employee_login_url','type' => 'login_admin_employee'], [
                'value' => $request->admin_employee_login_url
            ]);
        }
        elseif($request->type == 'store') {
            DataSetting::query()->updateOrInsert(['key' => 'store_login_url','type' => 'login_store'], [
                'value' => $request->store_login_url
            ]);
        }
        elseif($request->type == 'store_employee') {
            DataSetting::query()->updateOrInsert(['key' => 'store_employee_login_url','type' => 'login_store_employee'], [
                'value' => $request->store_employee_login_url
            ]);
        }
        Toastr::success(translate('messages.update_successfull'));
        return back();
    }

    public function remove_image(Request $request){

        $request->validate([
            'model_name' => 'required',
            'id' => 'required',
            'image_path' => 'required',
            'field_name' => 'required',
        ]);
        try {

            $model_name = $request->model_name;
            $model = app("\\App\\Models\\{$model_name}");
            $data=  $model->where('id', $request->id)->first();

            $data_value = $data?->{$request->field_name};

            if($request?->json == 1){
                $data_value = json_decode($data?->value ,true);
                if (Storage::disk('public')->exists($request->image_path.'/'.$data_value[$request->field_name])) {
                    Storage::disk('public')->delete($request->image_path.'/'.$data_value[$request->field_name]);
                }
                $data_value[$request->field_name] = null;
                $data->value = json_encode($data_value);
            }
            else{
                if (Storage::disk('public')->exists($request->image_path.'/'.$data_value)) {
                    Storage::disk('public')->delete($request->image_path.'/'.$data_value);
                }
                $data->{$request->field_name} = null;
            }

            $data?->save();

        } catch (\Throwable $th) {
            Toastr::error($th->getMessage(). 'Line....'.$th->getLine());
            return back();
        }
        Toastr::success(translate('messages.Image_removed_successfully'));
        return back();
    }

    public function react_setup()
    {
        Helpers::react_domain_status_check();
        return view('admin-views.business-settings.react-setup');
    }

    public function react_update(Request $request)
    {
        $request->validate([
            'react_license_code'=>'required',
            'react_domain'=>'required'
        ],[
            'react_license_code.required'=>translate('messages.license_code_is_required'),
            'react_domain.required'=>translate('messages.doamain_is_required'),
        ]);
        if(Helpers::activation_submit($request['react_license_code'])){
            DB::table('business_settings')->updateOrInsert(['key' => 'react_setup'], [
                'value' => json_encode([
                    'status'=>1,
                    'react_license_code'=>$request['react_license_code'],
                    'react_domain'=>$request['react_domain'],
                    'react_platform' => 'codecanyon'
                ])
            ]);

            Toastr::success(translate('messages.react_data_updated'));
            return back();
        }
        elseif(Helpers::react_activation_check($request->react_domain, $request->react_license_code)){

            DB::table('business_settings')->updateOrInsert(['key' => 'react_setup'], [
                'value' => json_encode([
                    'status'=>1,
                    'react_license_code'=>$request['react_license_code'],
                    'react_domain'=>$request['react_domain'],
                    'react_platform' => 'iss'
                ])
            ]);

            Toastr::success(translate('messages.react_data_updated'));
            return back();
        }
        Toastr::error(translate('messages.Invalid_license_code_or_unregistered_domain'));
        return back()->withInput(['invalid-data'=>true]);
    }

    public function landing_page_settings_update(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'landing_integration_via' => 'required',
            'redirect_url' => 'required_if:landing_integration_via,url',
            'file_upload' => 'mimes:zip'
        ]);

        if(!File::exists('resources/views/layouts/landing/custom/index.blade.php') && ($request->landing_integration_via == 'file_upload') && (!$request->file('file_upload'))){
            $validator->getMessageBag()->add('file_upload', translate('messages.zip_file_is_required'));
        }

        if ($validator->errors()->count() > 0) {
            $error = Helpers::error_processor($validator);
            return response()->json(['status' => 'error', 'message' => $error[0]['message']]);
        }

        DB::table('business_settings')->updateOrInsert(['key' => 'landing_integration_type'], [
            'value' => $request['landing_integration_via']
        ]);
        $status = 'success';
        $message = translate('updated_successfully!');

        if($request->landing_integration_via == 'file_upload'){

            $file = $request->file('file_upload');
            if($file){

                $filename = $file->getClientOriginalName();
                $tempPath = $file->storeAs('temp', $filename);
                $zip = new \ZipArchive();
                if ($zip->open(storage_path('app/' . $tempPath)) === TRUE) {
                    // Extract the contents to a directory
                    $extractPath = base_path('resources/views/layouts/landing/custom');
                    $zip->extractTo($extractPath);
                    $zip->close();
                    // dd(File::exists($extractPath.'/index.blade.php'));
                    if(File::exists($extractPath.'/index.blade.php')){
                        Toastr::success(translate('file_upload_successfully!'));
                        $status = 'success';
                        $message = translate('file_upload_successfully!');
                    }else{
                        File::deleteDirectory($extractPath);
                        $status = 'error';
                        $message = translate('invalid_file!');
                    }
                }else{
                    $status = 'error';
                    $message = translate('file_upload_fail!');
                }

                Storage::delete($tempPath);
            }
        }

        if($request->landing_integration_via == 'url'){
            DB::table('business_settings')->updateOrInsert(['key' => 'landing_page_custom_url'], [
                'value' => $request['redirect_url']
            ]);

            $status = 'success';
            $message = translate('url_saved_successfully!');
        }

        return response()->json([
            'status' => $status,
            'message'=> $message
        ]);
    }

    public function delete_custom_landing_page()
    {
        $filePath = 'resources/views/layouts/landing/custom/index.blade.php';

        if (File::exists($filePath)) {
            File::delete($filePath);
            Toastr::success(translate('messages.File_deleted_successfully'));
            return back();
        } else {
            Toastr::error(translate('messages.File_not_found'));
            return back();
        }
    }


    public static function product_approval_all()
    {
        $temp_data = TempProduct::where('is_rejected' , 0)->get();

        foreach($temp_data as $data){
            $item= Item::withoutGlobalScope('translate')->with('translations')->findOrfail($data->item_id);

            $item->name = $data->name;
            $item->description =  $data->description;
            $item->image = $data->image;
            $item->images = $data->images;

            $item->store_id = $data->store_id;
            $item->module_id = $data->module_id;
            $item->unit_id = $data->unit_id;

            $item->category_id = $data->category_id;
            $item->category_ids = $data->category_ids;

            $item->choice_options = $data->choice_options;
            $item->food_variations = $data->food_variations;
            $item->variations = $data->variations;
            $item->add_ons = $data->add_ons;
            $item->attributes = $data->attributes;

            $item->price = $data->price;
            $item->discount = $data->discount;
            $item->discount_type = $data->discount_type;

            $item->available_time_starts = $data->available_time_starts;
            $item->available_time_ends = $data->available_time_ends;
            $item->maximum_cart_quantity = $data->maximum_cart_quantity;
            $item->veg = $data->veg;

            $item->organic = $data->organic;
            $item->stock =  $data->stock;
            $item->is_approved = 1;

            $item->save();
            $item->tags()->sync(json_decode($data->tag_ids));
            if($item->module->module_type == 'pharmacy'){
                DB::table('pharmacy_item_details')
                    ->updateOrInsert(
                        ['item_id' => $item->id],
                        [
                            'common_condition_id' => $data->condition_id,
                            'is_basic' => $data->basic ?? 0,
                        ]
                    );
            }
            $item?->translations()?->delete();
            Translation::where('translationable_type' , 'App\Models\TempProduct')->where('translationable_id' , $data->id)->update([
                'translationable_type' => 'App\Models\Item',
                'translationable_id' => $item->id
            ]);

            $data->delete();
        }

        return true;
    }


}