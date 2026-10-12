@if (Auth::check())
    @if (Auth::user()->isWarga())
        @php
            $isShowPage = request()->routeIs('client.surat.show') && isset($suratRequest);
            $latestSuratUpdated = Auth::user()->suratRequests()->max('updated_at');
            $maxUpdatedTimestamp = $latestSuratUpdated ? \Carbon\Carbon::parse($latestSuratUpdated)->timestamp : 0;
            $totalSurat = Auth::user()->suratRequests()->count();
        @endphp

        <div id="saobi-live-sync"
             data-role="client"
             data-url="{{ route('client.live-check') }}"
             data-context="{{ $isShowPage ? 'show' : 'list' }}"
             @if($isShowPage)
                 data-id="{{ $suratRequest->id }}"
                 data-status="{{ $suratRequest->status }}"
                 data-updated="{{ $suratRequest->updated_at ? $suratRequest->updated_at->timestamp : 0 }}"
                 data-has-file="{{ !empty($suratRequest->file_surat) ? 'true' : 'false' }}"
             @else
                 data-max-updated="{{ $maxUpdatedTimestamp }}"
                 data-total="{{ $totalSurat }}"
             @endif
             style="display: none;">
        </div>
    @elseif (Auth::user()->canAccessAdminPanel())
        @php
            $isAdminMonitor = request()->routeIs('admin.dashboard') || request()->routeIs('admin.permohonan.index');
            $latestCreated = \App\Models\SuratRequest::max('created_at');
            $maxCreatedTimestamp = $latestCreated ? \Carbon\Carbon::parse($latestCreated)->timestamp : 0;
            $totalRequests = \App\Models\SuratRequest::count();
        @endphp

        @if ($isAdminMonitor)
            <div id="saobi-live-sync"
                 data-role="admin"
                 data-url="{{ route('admin.live-check') }}"
                 data-context="admin-monitor"
                 data-max-created="{{ $maxCreatedTimestamp }}"
                 data-total="{{ $totalRequests }}"
                 style="display: none;">
            </div>
        @endif
    @endif

    <!-- Container Notifikasi Real-time -->
    <div id="saobi-live-toast-container" class="fixed top-5 right-5 z-[99997] max-w-sm w-full pointer-events-none space-y-3 px-4 sm:px-0">
    </div>
@endif
