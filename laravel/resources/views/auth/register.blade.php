<x-guest-layout>
	<x-authentication-card>
		<x-validation-errors class="mb-4" />

		<form method="POST" action="{{ route('register') }}">
			@csrf

			<div>
				<x-label for="username" value="{{ __('Name') }}" />
				<x-input id="username" class="block mt-1 w-full" type="text" name="username" :value="old('name')" required autofocus autocomplete="username" />
			</div>

			<div class="mt-4">
				<x-label for="useridnumber" value="{{ __('ID Number') }}" />
				<x-input id="useridnumber" class="block mt-1 w-full" type="text" name="useridnumber" :value="old('useridnumber')" required autocomplete="useridnumber" />
			</div>

			<div class="mt-4">
				<x-label for="userrole" value="{{ __('User Role') }}" />
				<x-input id="userrole" class="block mt-1 w-full" type="text" name="userrole" :value="old('userrole')" required autocomplete="userrole" />
			</div>

			<div class="mt-4">
				<x-label for="password" value="{{ __('Password') }}" />
				<x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
			</div>

			<div class="mt-4">
				<x-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
				<x-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
			</div>

			<div class="flex items-center justify-end mt-4">
				<a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
					{{ __('Already registered?') }}
				</a>

				<x-button class="ms-4">
					{{ __('Register') }}
				</x-button>
			</div>
		</form>
	</x-authentication-card>
</x-guest-layout>