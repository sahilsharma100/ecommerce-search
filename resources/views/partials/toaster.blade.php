@push('js')
    <!-- Toaster Alerts Js Cdn-->
    <script type="text/javascript" src="{{ asset('js/jquery.toaster.js') }}"></script>
    <script>
        function toaster(message, title, priority) {
            $.toaster({
                message: message,
                title: title,
                priority: priority,
            });
        }
    </script>

    @if (Session::has('error'))
        <script>
            toaster("{{ Session::get('error') }}", 'Error', 'danger');
        </script>
    @endif

    @if (Session::has('success'))
        <script>
            toaster("{{ Session::get('success') }}", 'Success', 'success');
        </script>
    @endif

    @if(count($errors) > 0 )
        @foreach($errors->all() as $error)
            <script>
                toaster("{{ $error }}", 'Validation Error', 'danger');
            </script>
        @endforeach
    @endif

@endpush
