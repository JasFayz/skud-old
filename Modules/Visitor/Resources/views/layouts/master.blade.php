@extends('admin::layouts.master')

@vite('Modules/Visitor/Resources/assets/js/app.js')

<script type="text/javascript">
    window.Laravel = {
        csrfToken: "{{ csrf_token() }}",
        jsPermissions: {!! auth()->user()?->jsPermissions() !!}
    }
</script>
