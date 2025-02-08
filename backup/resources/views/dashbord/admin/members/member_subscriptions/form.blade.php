@php use Illuminate\Support\Facades\App; @endphp
@extends('dashbord.layouts.master')
@section('toolbar')
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">{{trans('sub.main_subscriptions')}}</h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted"><a href="{{ route('admin.dashboard') }}"
                                                          class="text-muted text-hover-primary">{{trans('Toolbar.home')}}</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">{{trans('Toolbar.subscriptions')}}</li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">{{trans('sub.member_subscriptions')}}</li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">{{trans('sub.add_new_subscription')}}</li>
            </ul>
        </div>

        <div class="d-flex align-items-center gap-2 gap-lg-3">
            <div class="d-flex">
                <a class="btn btn-icon btn-sm btn-primary flex-shrink-0 ms-4"
                   href="{{route('admin.subscriptions.member-subscriptions.index')}}">
                    {{--                    <i class="bi bi-arrow-clockwise ">{{trans('sub.back')}}</i>--}}
                    <!--begin::Svg Icon | path: /var/www/preview.keenthemes.com/keenthemes/keen/docs/core/html/src/media/icons/duotune/arrows/arr054.svg-->
                    <span class="svg-icon svg-icon-2">
                                   <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                       <path
                                           d="M17.6 4L9.6 12L17.6 20H13.6L6.3 12.7C5.9 12.3 5.9 11.7 6.3 11.3L13.6 4H17.6Z"
                                           fill="currentColor"/>
                                   </svg>
                                </span>
                    <!--end::Svg Icon-->
                </a>
            </div>
        </div>
    </div>

@endsection
@section('content')

    <div id="kt_app_content_container" class="t_container">
        <div class="card shadow-sm ">
            <div class="card-header">
                <h3 class="card-title"></i> {{trans('sub.add_new_subscription')}}</h3>

            </div>

            @php
                if (empty($members_subscriptions))
                  {
                      $action=route('admin.subscriptions.member-subscriptions.store');
                      $member_id='';
                      $end_date='';
                      $start_date=date('Y-m-d');
                      $pay_method='';
                      $main_subscription_id='';
                      $main_discount='';
                      $package_price='';
                      $package_duration='';
                      $transportation='';
                      $transport_price='';
                      $transport_duration='';
                      $disabled='';
                      $readonly='';


                   }
                   else{
                      $action=route('admin.subscriptions.add_additional_subscriptions',$members_subscriptions->id);
                      $member_id=$members_subscriptions->member_id;
                      $end_date=$members_subscriptions->end_date;
                      $start_date=$members_subscriptions->start_date;
                      $pay_method=$members_subscriptions->pay_method;
                      $main_subscription_id=$members_subscriptions->subscription_id;
                      $main_discount=$members_subscriptions->discount;
                      $package_price=$members_subscriptions->main_subscriptions->price;
                      $package_duration=$members_subscriptions->main_subscriptions->duration;
                      $transportation=$members_subscriptions->transport;
                      $transport_price=$members_subscriptions->transport_value;
                      $transport_duration=$members_subscriptions->main_subscriptions->duration;
                      $readonly='readonly';
                      $disabled='disabled';

                   }
            @endphp


            <form id="save_form" method="post" action="{{ $action }}"
                  enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>

                    @endif

                    <div class="row" style="margin-top: 20px">

                        <div class="col-md-3  mb-5">
                            <label class="required fs-6 fw-semibold mb-2">{{trans('members.member_name')}}</label>
                            <select class="form-control " data-control="select2" {{$disabled}}
                            name="member_id" id="member_id">
                                <option value=" ">{{trans('forms.select')}}</option>

                                @foreach($members as $key)
                                    <option
                                        value="{{$key->id}}"
                                        @if(old('member_id',$key->id)==$member_id) selected @endif> {{$key->member_name}}</option>
                                @endforeach

                            </select>
                        </div>

                        <div class="col-md-3  mb-5">
                            <label class="required fs-6 fw-semibold mb-2">{{trans('members.main_subscription')}}</label>
                            <select onchange="get_sub_details_main(this.value)"
                                    class="form-control  subscription-select" data-control="select2" {{$disabled}}
                                    name="main_subscription_id" id="main_subscription_id">
                                <option value=" ">{{trans('forms.select')}}</option>
                                @foreach($main_subscriptions as $item)
                                    <option value="{{$item->id}}"
                                            @if(old('main_subscription_id',$item->id)==$main_subscription_id) selected @endif> {{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3  mb-5">
                            <label
                                class="required fs-6 fw-semibold mb-2">{{trans('members.subscription_start_date')}}</label>
                            <input onchange="get_sub_details_main(this.value)"
                                   class="form-control " {{$readonly}}
                                   name="main_start_date"
                                   type="date"
                                   value="{{$start_date}}"  id="main_start_date"/>

                        </div>
                        <div class="col-md-3  mb-5" id="end_date_dev" style="display: block">
                            <label
                                class="required fs-6 fw-semibold mb-2">{{trans('members.subscription_end_date')}}</label>
                            <input class="form-control " {{$readonly}}
                            name="end_date"
                                   type="date"
                                   value="{{$end_date}}" id="end_date" readonly/>

                        </div>


                    </div>


                    <div class="row mt-10">

                        <div class="col-md-3  mb-5">
                            <label
                                class="required fs-6 fw-semibold mb-2">{{trans('members.discount')}}
                                (<span
                                    style="color: darkred" id="max_discount"></span>)</label>
                            <input onkeyup="checkMaxDiscount_main(this.value)" class="form-control "
                                   name="main_discount" {{$readonly}}
                                   type="number" step="any" min="0"
                                   value="{{$main_discount}}" id="discount"/>
                        </div>
                        <input type="hidden" name="main_discount_hidden" id="main_discount_hidden">
                        <div class="col-md-3 mb-5">
                            <label class="required fs-6 fw-semibold mb-2">{{trans('members.package_duration')}}</label>
                            <input class="form-control " {{$readonly}}
                            name="package_duration"
                                   type="text" step="any"
                                   value="{{$package_duration}}" id="package_duration" readonly/>
                        </div>
                        <div class="col-md-3  mb-5">
                            <label class="required fs-6 fw-semibold mb-2">{{trans('members.package_price')}}</label>
                            <input class="form-control "
                                   name="package_price" {{$readonly}}
                                   type="text" step="any"
                                   value="{{$package_price}}" id="package_price" readonly/>
                        </div>
                        <div class="col-md-3  mb-5">
                            <label class="required fs-6 fw-semibold mb-2">{{trans('members.transportation')}}</label>
                            <select onchange="transport_type(this.value)" {{$disabled}}
                            class="form-control" data-control="select2"
                                    name="transportation" id="transportation">
                                <?php $pay_method_arr = ['yes' => trans('members.subscribed'), 'no' => trans('members.not_subscribed')] ?>
                                <option value=" ">{{trans('forms.select')}}</option>
                                @foreach($pay_method_arr as $key=>$value)
                                    <option value="{{$key}}"
                                            @if(old('transportation',$key)==$transportation) selected @endif> {{$value}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row" style="margin-top: 10px">

                        <div class="col-md-3 mb-5">
                            <label
                                class="required fs-6 fw-semibold mb-2">{{trans('members.transport_duration')}}</label>
                            <input class="form-control " {{$readonly}}
                            name="transport_duration"
                                   type="text" step="any"
                                   value="{{$transport_duration}}" id="transport_duration" readonly/>
                        </div>
                        <div class="col-md-3  mb-5">
                            <label class="required fs-6 fw-semibold mb-2">{{trans('members.transport_price')}}</label>
                            <input class="form-control " {{$readonly}}
                            name="transport_price"
                                   type="text" step="any"
                                   value="{{$transport_price}}" id="transport_price" readonly/>
                        </div>

                        <input type="hidden" id="transport_duration_hidden" value="{{$transport_duration}}">
                        <input type="hidden" id="transport_price_hidden" value="{{$transport_price}}">


                        <div class="col-md-3  mb-5">
                            <label
                                class="required fs-6 fw-semibold mb-2">{{trans('members.pay_method')}}</label>
                            <select onchange="pay_type1(this)" data-control="select2" {{$disabled}}
                            class="form-control  pay-method-select"
                                    name="pay_method" id="pay_method">
                                <?php $pay_method_arr = ['cache' => trans('members.cache'), 'visa' => trans('members.visa'), 'bank' => trans('members.bank')] ?>
                                <option value=" ">{{trans('forms.select')}}</option>
                                @foreach($pay_method_arr as $key=>$value)
                                    <option value="{{$key}}"
                                            @if(old('pay_method',$key)==$pay_method) selected @endif> {{$value}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3  mb-5 transfer-image-dev" style="display: none">
                            <label
                                class="required fs-6 fw-semibold mb-2">{{trans('members.transfer_image')}}</label>
                            <input class="form-control " type="file"
                                   name="transfer_image" id="transfer_image" accept="image/*">
                        </div>

                    </div>
                    <br>
                    <h3 class="card-title"></i> {{trans('sub.additional_subscriptions')}}</h3>
                    <hr>


                    <input type="hidden" name="process_num" value="{{$process_num+1}}">
                    <div id="kt_docs_repeater_advanced">
                        <!--begin::Form group-->
                        <div class="form-group">
                            <div data-repeater-list="kt_docs_repeater_advanced">
                                <div data-repeater-item>
                                    <div class="form-group row mb-5">
                                        <div class="row" style="margin-top: 10px">
                                            {{--                                            <input type="hidden" name="type" id="type" value="special"  onkeyup="get_subscription(this)">--}}
                                            <div class="col-md-2  mb-5">
                                                <label class="form-label">{{trans('members.category')}}</label>
                                                <select onchange="get_subscription(this)"
                                                        class="form-control  type-select"
                                                        name="type" id="type">
                                                    <option value=" ">{{trans('forms.select')}}</option>
                                                    @php $cat_arr=['special'=>trans('members.special_subscription')] @endphp
                                                    @foreach($cat_arr as $key=>$value)
                                                        <option value="{{$key}}"> {{$value}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-2  mb-5">
                                                <label
                                                    class="required fs-6 fw-semibold mb-2">{{trans('members.subscription')}}</label>
                                                <select onchange="get_sub_details(this)"
                                                        class="form-control  subscription-select"
                                                        name="subscription_id" id="subscription_id">
                                                    <option value=" ">{{trans('forms.select')}}</option>
                                                </select>
                                            </div>
                                            <div class="col-md-2  mb-5">
                                                <label
                                                    class="required fs-6 fw-semibold mb-2">{{trans('members.subscription_start_date')}}</label>
                                                <input onchange="check_start_date(this)"
                                                       class="form-control "
                                                       name="start_date"
                                                       type="date"
                                                       value="{{date('Y-m-d')}}"
                                                       id="start_date"/>

                                            </div>
                                            <div class="col-md-2  mb-5" id="trainer_dev">
                                                <label
                                                    class="required fs-6 fw-semibold mb-2">{{trans('members.trainers')}}</label>
                                                <select class="form-control "
                                                        name="trainer_id" id="trainer_id">
                                                    <option value=" ">{{trans('forms.select')}}</option>

                                                    @foreach($trainers as $key)
                                                        <option value="{{$key->id}}"> {{$key->user_name}}</option>
                                                    @endforeach

                                                </select>
                                            </div>
                                            <div class="col-md-1 mb-5">
                                                <label
                                                    class="required fs-6 fw-semibold mb-2">{{trans('sub.duration')}}</label>
                                                <input type="number" readonly class="form-control " name="duration"
                                                       id="duration">

                                            </div>
                                            <div class="col-md-1 mb-5">
                                                <label
                                                    class="required fs-6 fw-semibold mb-2">{{trans('members.cost')}}</label>
                                                <input type="number" readonly class="form-control p-3 sub2cost " name=""
                                                       id="cost">

                                            </div>
                                            <div class="col-md-2 mb-5">
                                                <label
                                                    class="required fs-6 fw-semibold mb-2">{{trans('members.discount')}}
                                                    (<span class="text-danger text-sm"
                                                           style="/*color: darkred*/" id="max_discount"></span>)</label>
                                                <input onkeyup="checkMaxDiscount(this)" class="form-control "
                                                       name="discount" min="0"
                                                       type="number" step="any"
                                                       value="" id="discount"/>
                                            </div>
                                            <input type="hidden" id="max_sub_dicount" name="max_sub_dicount" value="">


                                            <div class="col-md-1 d-flex align-items-center gap-2 gap-lg-3">
                                                <div class="d-flex">
                                                    <a href="javascript:" data-repeater-delete
                                                       class="btn btn-sm btn-icon btn-light-danger mt-3 mt-md-9 flex-shrink-0 ms-4">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </div>
                                            </div>

                                        </div>


                                    </div>


                                    <br>
                                    <hr>
                                </div>

                            </div>
                        </div>
                        <!--end::Form group-->
                        <!--begin::Form group-->

                        <div class="d-flex align-items-center gap-2 gap-lg-3">
                            <div class="d-flex">
                                <a data-repeater-create id="create-repeater-btn"
                                   class="btn btn-icon btn-sm btn-success flex-shrink-0 ms-4">
                                    <!--begin::Svg Icon | path: icons/duotune/arrows/arr075.svg-->
                                    <span class="svg-icon svg-icon-2">
													<svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                         xmlns="http://www.w3.org/2000/svg">
														<rect opacity="0.5" x="11.364" y="20.364" width="16" height="2"
                                                              rx="1" transform="rotate(-90 11.364 20.364)"
                                                              fill="currentColor"/>
														<rect x="4.36396" y="11.364" width="16" height="2" rx="1"
                                                              fill="currentColor"/>
                                                    </svg>
					                           </span>

                                </a>
                                <span id="danger_msg" style="margin-right: 20px" class="text-danger"></span>

                            </div>


                        </div>


                        <!--end::Form group-->
                    </div>


                    <div class="d-flex justify-content-end">
                        <button type="submit" id="" class="btn btn-primary">
                            <span class="indicator-label">{{trans('forms.save_btn')}}</span>
                            <span class="indicator-progress">Please wait...
							<span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                        </button>
                    </div>
                </div>


            </form>

        </div>


    </div>

@stop
@section('js')

    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\Admin\subscription\member_subscriptions\SaveMemberSubscriptions', '#save_form'); !!}
    <script src="{{asset('assets/plugins/custom/ckeditor/ckeditor-classic.bundle.js')}}"></script>
    <script src="{{asset('assets/plugins/custom/formrepeater/formrepeater.bundle.js')}}"></script>
    <script>
        var KTAppBlogSave = function () {
            const initTagify = () => {
                // The DOM elements you wish to replace with Tagify
                var input1 = document.querySelector("#details_tag_ar");
                var input2 = document.querySelector("#details_tag_en");

// Initialize Tagify components on the above inputs
                new Tagify(input1);
                new Tagify(input2);

            };
            const initckeditor = () => {

                const elements_en = [
                    '#details_en'
                ];
                const elements_ar = [
                    '#details_ar'
                ];

                // Loop all elements
                elements_en.forEach((element, index) => {
                    // Get quill element
                    let ckeditor = document.querySelector(element);

                    // Break if element not found
                    if (!ckeditor) {
                        return;
                    }

                    // Init quill --- more info: https://quilljs.com/docs/quickstart/
                    ClassicEditor
                        .create(ckeditor, {
                            toolbar: {
                                items: [
                                    'undo', 'redo',
                                    '|', 'heading',
                                    '|', 'bold', 'italic',
                                    '|', 'bulletedList', 'numberedList', 'outdent', 'indent'
                                ]
                            }, heading: {
                                options: [
                                    {model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph'},
                                    {model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1'},
                                    {model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2'},
                                    {model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3'}
                                ]
                            }, language: 'en'
                        })
                        .then(editor => {
                            console.log(editor);
                        })
                        .catch(error => {
                            console.error(error);
                        });


                });
                // Loop all elements
                elements_ar.forEach((element, index) => {
                    // Get quill element
                    let ckeditor = document.querySelector(element);

                    // Break if element not found
                    if (!ckeditor) {
                        return;
                    }

                    // Init quill --- more info: https://quilljs.com/docs/quickstart/
                    ClassicEditor
                        .create(ckeditor, {
                            toolbar: {
                                items: [
                                    'undo', 'redo',
                                    '|', 'heading',
                                    '|', 'bold', 'italic',
                                    '|', 'bulletedList', 'numberedList', 'outdent', 'indent'
                                ]
                            }, heading: {
                                options: [
                                    {model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph'},
                                    {model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1'},
                                    {model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2'},
                                    {model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3'}
                                ]
                            }, language: 'ar'
                        })
                        .then(editor => {
                            console.log(editor);
                        })
                        .catch(error => {
                            console.error(error);
                        });


                });

            }

            // Public methods
            return {
                init: function () {
                    // Init forms
                    initckeditor();
                    initTagify();
                }
            };
        }();
        // On document ready
        KTUtil.onDOMContentLoaded(function () {
            KTAppBlogSave.init();

        });

    </script>
    <script>
        $(document).ready(function () {
            checkMainSubscription();
            $('#main_subscription_id').on('change', function () {
                checkMainSubscription();
            });


        });
        /* $(document).on('click', '.create-repeater-btn', function() {
             // Find the closest element containing the hidden input with name="type"
             var closestTypeInput = $(this).find('input[type="hidden"][name="type"]');

             // Trigger the change event on the found input
             closestTypeInput.trigger('keyup');

             // Call the get_subscription function with the closest input
             get_subscription(closestTypeInput);
         });*/


    </script>


    {{--  <script>
          function get_subscription(type,subscription_id) {
              console.log('subscription_id'+subscription_id);
              $.ajax({
                  url: '{{route('admin.get-subscription')}}',
                  type: 'get',
                  data: {
                      type: type,
                  },
                  success: function (response) {
                      $('#subscription_id').empty();
                      $('#subscription_id').append('<option>{{ trans('forms.select') }}</option>');
                      var currentLocale = '{{ app()->getLocale() }}';
                      response.forEach(function (subscription) {
                          var name = subscription.name[currentLocale]; // Access the translation for the current locale
                          $('#subscription_id').append('<option value="' + subscription.id + '">' + name + '</option>');
                          if(subscription_id !=' ')
                          {
                              $('#subscription_id2').append('<option value="' + subscription.id + '">' + name + '</option>');
                              $('#subscription_id').val(subscription_id);
                          }

                      });

                      if (type == 'special')
                      {
                          $('#trainer_dev').show()
                          $('#end_date_dev').hide()
                      }else {
                          $('#trainer_dev').hide()
                          $('#end_date_dev').show()
                      }
                  },
                  error: function (xhr, status, error) {
                      // Handle any errors here
                      console.error(error);
                  }
              });
          }

      </script>

      <script>
          function get_sub_details(id)
          {
              var type=$('#type').val();
              var subscription_id=$('#subscription_id').val();
              var start_date=$('#start_date').val();
              $.ajax({
                  url: '{{route('admin.get-subscription-details')}}',
                  type: 'get',
                  data: {
                      type: type,
                      id: subscription_id,
                      start_date: start_date,
                  },
                  success: function (response) {

                      console.log(response.subscription.max_discount);
                      if(type == 'main')
                      {
                          $('#end_date').val(response.end_date);
                      }

                      $('#max_discount').text('{{ trans('members.max_discount') }}'+' ' +response.subscription.max_discount+ ' % ');


                  },
                  error: function (xhr, status, error) {

                      console.error(error);
                  }
              });
          }
      </script>

      <script>
          function pay_type(id)
          {
              if (id == 'bank')
              {
                  $('#transfer_image_dev').show();
              }else{
                  $('#transfer_image_dev').hide();
              }
          }
      </script>--}}

    <script>
        function checkMaxDiscount_main(input) {
            var max_discount = parseFloat($('#main_discount_hidden').val());
            var current_value = parseFloat(input);

            console.log('max_discount : ' + max_discount)
            console.log('current_value : ' + current_value)

            if (current_value > max_discount) {
                Swal.fire({
                    title: "{{trans('members.max_discount_message')}}",
                    icon: "warning",
                    iconHtml: "؟",
                    confirmButtonText: "{{trans('forms.action_done')}}",
                });
                $('#discount').val(max_discount);


                /* if (confirm('The discount value cannot exceed ' + max_discount + '. Do you want to set it to the maximum allowed?')) {
                     $('#discount').val(max_discount);
                 } else {
                     $('#discount').val(0);
                 }*/
            }
        }
    </script>

    <script>
        function get_sub_details_main(id) {
            var type = 'main';
            var subscription_id = $('#main_subscription_id').val()
            var start_date = $('#main_start_date').val();
            var transportation = $('#transportation').val();
            $.ajax({
                url: '{{route('admin.get-subscription-details')}}',
                type: 'get',
                data: {
                    type: type,
                    id: subscription_id,
                    start_date: start_date,
                },
                success: function (response) {

                    console.log(response.subscription.max_discount);
                    if (type == 'main') {
                        $('#end_date').val(response.end_date);
                        $('#package_duration').val(response.subscription.duration);
                        $('#package_price').val(response.subscription.price);
                        $('#main_discount_hidden').val(response.subscription.max_discount);


                        if (transportation == 'yes') {
                            $('#transport_duration').val(response.subscription.duration);
                            $('#transport_price').val(response.transport_price);

                        } else {
                            $('#transport_duration').val(0);
                            $('#transport_price').val(0);
                        }

                        $('#transport_duration_hidden').val(response.subscription.duration);
                        $('#transport_price_hidden').val(response.transport_price);
                    }

                    $('#max_discount').text(response.subscription.max_discount + ' % ');


                },
                error: function (xhr, status, error) {

                    console.error(error);
                }
            });
        }
    </script>

    <script>
        function transport_type(type) {
            var duration = $('#transport_duration_hidden').val();
            var price = $('#transport_price_hidden').val();
            if (type == 'no') {
                $('#transport_duration').val(0);
                $('#transport_price').val(0);

            } else {
                $('#transport_duration').val(duration);
                $('#transport_price').val(price);
            }

        }
    </script>

    <script>
        function pay_type1(id) {
            console.log('id' + id.value);
            if (id.value == 'bank') {
                $('.transfer-image-dev').show();
            } else {
                $('.transfer-image-dev').hide();
            }
        }
    </script>

    <script>
        function get_subscription(element) {
            var $repeaterItem = $(element).closest('[data-repeater-item]');
            var type = $(element).val();
            var $subscriptionSelect = $repeaterItem.find('.subscription-select');
            var subscription_id = ' '; // Placeholder, adjust as needed

            console.log('subscription_id' + subscription_id);
            $.ajax({
                url: '{{route('admin.get-subscription')}}',
                type: 'get',
                data: {
                    type: type,
                },
                success: function (response) {
                    $subscriptionSelect.empty();
                    $subscriptionSelect.append('<option>{{ trans('forms.select') }}</option>');
                    var currentLocale = '{{ app()->getLocale() }}';
                    response.forEach(function (subscription) {
                        var name = subscription.name[currentLocale]; // Access the translation for the current locale
                        $subscriptionSelect.append('<option value="' + subscription.id + '">' + name + '</option>');
                        if (subscription_id != ' ') {
                            $subscriptionSelect.append('<option value="' + subscription.id + '">' + name + '</option>');
                            $subscriptionSelect.val(subscription_id);
                        }
                    });

                    if (type == 'special') {
                        $repeaterItem.find('#trainer_dev').show();
                        $repeaterItem.find('#end_date_dev').hide();
                    } else {
                        $repeaterItem.find('#trainer_dev').hide();
                        $repeaterItem.find('#end_date_dev').show();
                    }
                },
                error: function (xhr, status, error) {
                    console.error(error);
                }
            });
        }

        function get_sub_details(element) {
            var $repeaterItem = $(element).closest('[data-repeater-item]');
            var type = $repeaterItem.find('#type').val();
            var subscription_id = $repeaterItem.find('#subscription_id').val();
            var start_date = $repeaterItem.find('#start_date').val(); // Assuming there's a global start_date element
            $.ajax({
                url: '{{route('admin.get-subscription-details')}}',
                type: 'get',
                data: {
                    type: type,
                    id: subscription_id,
                    start_date: start_date,
                },
                success: function (response) {
                    console.log(response.subscription.max_discount);
                    if (type == 'main') {
                        $repeaterItem.find('#end_date').val(response.end_date);
                    }
                    $repeaterItem.find('#duration').val(response.subscription.duration);
                    $repeaterItem.find('#sub_duration').text(response.subscription.duration);
                    $repeaterItem.find('#sub_price').text(response.subscription.price);
                    $repeaterItem.find('#sub_discount').text(response.subscription.max_discount);
                    $repeaterItem.find('#max_sub_dicount').val(response.subscription.max_discount);
                    $repeaterItem.find('#cost').val(response.subscription.price);

                    var local = '{{App::getLocale()}}';
                    $repeaterItem.find('#sub_name').text(response.subscription.name[local]);
                    $repeaterItem.find('#max_discount').text(response.subscription.max_discount + ' % ');
                },
                error: function (xhr, status, error) {
                    console.error(error);
                }
            });
        }


        function pay_type(element) {
            var $repeaterItem = $(element).closest('[data-repeater-item]');
            var id = $(element).val();
            var $transferImageDev = $repeaterItem.find('.transfer-image-dev');

            if (id === 'bank') {
                $transferImageDev.show();
            } else {
                $transferImageDev.hide();
            }
        }


    </script>

    <script>
        function checkMaxDiscount(input) {
            var $repeaterItem = $(input).closest('[data-repeater-item]');
            var discount = parseFloat(input.value);
            var maxDiscount = parseFloat($repeaterItem.find('#max_sub_dicount').val());
            var $errorMessage = $repeaterItem.find('.error-message');

            console.log('discount' + discount);
            console.log('maxDiscount' + maxDiscount);

            if (isNaN(discount) || discount < 0) {
                $errorMessage.hide();
                return;
            }


            if (discount > maxDiscount) {


                Swal.fire({
                    title: "{{trans('members.max_discount_message')}}",
                    icon: "warning",
                    iconHtml: "؟",
                    confirmButtonText: "{{trans('forms.action_done')}}",
                });
                $repeaterItem.find('#discount').val(maxDiscount);

                /*  if (confirm('The discount value cannot exceed ' + max_discount + '. Do you want to set it to the maximum allowed?')) {
                      $repeaterItem.find('#discount').val(maxDiscount);
                  } else {
                      $repeaterItem.find('#sub_duration').val(0);
                  }*/
            }


        }
    </script>

    <script>
        $('#kt_docs_repeater_advanced').repeater({
            initEmpty: true,

            defaultValues: {
                'text-input': 'foo'
            },

            show: function () {
                $(this).slideDown();

                checkMainSubscription();
            },

            hide: function (deleteElement) {
                $(this).slideUp(deleteElement);
            },

            ready: function () {
                // Init select2


            }
        });
    </script>

    <script>
        function checkMainSubscription() {
            var mainSubscriptionId = $('#main_subscription_id').val();
            if (mainSubscriptionId && mainSubscriptionId !== ' ') {
                $('#create-repeater-btn').removeClass('disabled');
                $('#danger_msg').text('');
            } else {
                $('#create-repeater-btn').addClass('disabled');
                $('#danger_msg').text('{{trans('members.you_should_choose_main_subscription_first')}}');
            }
        }
    </script>
    <script>
        function check_start_date(element) {
            var $repeaterItem = $(element).closest('[data-repeater-item]');
            var start_date = $(element).val();
            var end_date = $('#end_date').val();

            if (start_date > end_date) {
                Swal.fire({
                    text: "{{trans('members.start_date_should_be_less_than_end_date')}}?",
                    icon: "warning",
                    buttonsStyling: false,
                    confirmButtonText: "{{trans('forms.ok')}}",
                    cancelButtonText: "{{trans('forms.action_no')}}",
                    customClass: {
                        confirmButton: "btn fw-bold btn-danger",
                        cancelButton: "btn fw-bold btn-active-light-primary"
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        $(element).val(''); // Clear the start_date field
                    }
                });
            }
        }
    </script>

@endsection
