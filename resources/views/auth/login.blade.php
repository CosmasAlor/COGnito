@extends('layouts.auth2')
@section('title', __('lang_v1.login'))
@inject('request', 'Illuminate\Http\Request')
@section('content')
    @php
        $username = old('username');
        $password = null;

    @endphp
    <div class="row">
        <div class="col-md-4">
        @if (config('app.env') == 'demo')
        
            
        
    @endif
        </div>
        <div class="col-md-4">
            <div
                class="tw-p-5 md:tw-p-6 tw-mb-4 tw-rounded-2xl tw-transition-all tw-duration-200 tw-bg-white tw-shadow-sm tw-ring-1 tw-ring-gray-200">
                <div class="tw-flex tw-flex-col tw-gap-4 tw-dw-rounded-box tw-dw-p-6 tw-dw-max-w-md">
                    <div class="tw-flex tw-items-center tw-flex-col">

						<img src="{{ ('uploads/img/coglogo.png')}}" alt="lock" class="tw-object-fill" width="40%"/>

                  
                    </div>

                    <form method="POST" action="{{ route('login') }}" id="login-form">
                        {{ csrf_field() }}
                        <div class="form-group has-feedback {{ $errors->has('username') ? ' has-error' : '' }}">
                            <label class="tw-dw-form-control">
                                <div class="tw-dw-label">
                                    <span
                                        class="tw-text-xs md:tw-text-sm tw-font-medium tw-text-black">@lang('lang_v1.username')</span>
                                </div>

                                <input
                                    class="tw-border tw-border-[#D1D5DA] tw-outline-none tw-h-12 tw-bg-transparent tw-rounded-lg tw-px-3 tw-font-medium tw-text-black placeholder:tw-text-gray-500 placeholder:tw-font-medium"
                                    name="username" required autofocus placeholder="@lang('lang_v1.username')"
                                    data-last-active-input="" id="username" type="text" name="username"
                                    value="{{ $username }}" />
                                @if ($errors->has('username'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                                @endif
                            </label>
                        </div>

                        <div class="form-group has-feedback {{ $errors->has('password') ? ' has-error' : '' }}">
                            <label class="tw-dw-form-control">
                                <div class="tw-dw-label">
                                    <span
                                        class="tw-text-xs md:tw-text-sm tw-font-medium tw-text-black">@lang('lang_v1.password')</span>
                                    @if (config('app.env') != 'demo')
                                        <a href="{{ route('password.request') }}"
                                            class="tw-text-xs md:tw-text-sm tw-font-medium tw-bg-gradient-to-r tw-from-indigo-500 tw-to-blue-500 tw-inline-block tw-text-transparent tw-bg-clip-text hover:tw-text-[#467BF5]"
                                            tabindex="-1">@lang('lang_v1.forgot_your_password')</a>
                                    @endif
                                </div>

                                <input
                                    class="tw-border tw-border-[#D1D5DA] tw-outline-none tw-h-12 tw-bg-transparent tw-rounded-lg tw-px-3 tw-font-medium tw-text-black placeholder:tw-text-gray-500 placeholder:tw-font-medium"
                                    id="password" type="password" name="password" value="{{ $password }}" required
                                    placeholder="@lang('lang_v1.password')" />
                                <button type="button" id="show_hide_icon" class="show_hide_icon"
                                    style="position: absolute; top:48px;right:5px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-eye tw-w-6" viewBox="0 0 24 24" stroke-width="1.5" stroke="#000000" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                        <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                    </svg>
                                </button>
                            </label>
                            @if ($errors->has('password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>


                        <div class="tw-dw-form-control">
                            <label class="tw-dw-cursor-pointer tw-dw-label tw-self-start tw-gap-2">
                                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}
                                    class="tw-dw-checkbox">
                                <span
                                    class="tw-text-xs md:tw-text-sm tw-font-medium tw-text-black tw-mt-[0.2rem]">@lang('lang_v1.remember_me')</span>
                            </label>
                        </div>
                        @if(config('constants.enable_recaptcha'))
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="g-recaptcha" data-sitekey="{{ config('constants.google_recaptcha_key') }}"></div>
                                        @if ($errors->has('g-recaptcha-response'))
                                            <span class="text-danger">{{ $errors->first('g-recaptcha-response') }}</span>
                                        @endif
                                </div>  
                            </div>
                        </div>
                        @endif
                        <button type="submit"
                            class="tw-bg-gradient-to-r tw-from-indigo-500 tw-to-blue-500 tw-h-12 tw-rounded-xl tw-text-sm md:tw-text-base tw-text-white tw-font-semibold tw-w-full tw-max-w-full mt-2 hover:tw-from-indigo-600 hover:tw-to-blue-600 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-blue-500 focus:tw-ring-offset-2 active:tw-from-indigo-700 active:tw-to-blue-700">
                            @lang('lang_v1.login')
                        </button>
                    </form>

                </div>
            </div>
        </div>
        <div class="col-md-4"></div>
    </div>

@stop
@section('javascript')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#show_hide_icon').off('click');
            $('.change_lang').click(function() {
                window.location = "{{ route('login') }}?lang=" + $(this).attr('value');
            });
            $('a.demo-login').click(function(e) {
                e.preventDefault();
                $('#username').val($(this).data('admin'));
                $('#password').val("{{ $password }}");
                $('form#login-form').submit();
            });

            $('#show_hide_icon').on('click', function(e) {
            e.preventDefault();
            const passwordInput = $('#password');

            if (passwordInput.attr('type') === 'password') {
                passwordInput.attr('type', 'text');
                $('#show_hide_icon').html('<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-eye-off tw-w-6" viewBox="0 0 24 24" stroke-width="1.5" stroke="#000000" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10.585 10.587a2 2 0 0 0 2.829 2.828"/><path d="M16.681 16.673a8.717 8.717 0 0 1 -4.681 1.327c-3.6 0 -6.6 -2 -9 -6c1.272 -2.12 2.712 -3.678 4.32 -4.674m2.86 -1.146a9.055 9.055 0 0 1 1.82 -.18c3.6 0 6.6 2 9 6c-.666 1.11 -1.379 2.067 -2.138 2.87"/><path d="M3 3l18 18"/></svg>');
            }
            else if (passwordInput.attr('type') === 'text') {
                passwordInput.attr('type', 'password');
                $('#show_hide_icon').html('<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-eye tw-w-6" viewBox="0 0 24 24" stroke-width="1.5" stroke="#000000" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"/><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6"/></svg>');
            }
        });
        })
    </script>
@endsection
