@extends('layouts.app')

@section("content")
@forelse ($assets as $asset)
<div
  class="block rounded-lg bg-white p-6 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700">
  <h5
    class="mb-2 text-xl font-medium leading-tight text-neutral-800 dark:text-neutral-50">
    {{ $asset->filename }}
  </h5>
  <div class="self-center">
  <video width="320" height="240" controls>
      <source src="{{ asset("storage/$asset->url") }}" type="video/mp4">
  </video>
  </div>
</div>
@empty
@endforelse
@endsection