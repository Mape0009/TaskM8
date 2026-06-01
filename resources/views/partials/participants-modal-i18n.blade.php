@php
$participantsModalI18n = [
    'close' => __('ui.close'),
    'templateTitle' => __('ui.template_title'),
    'templateTitleFor' => __('ui.template_title_for'),
    'participantsSubtitleFor' => __('ui.participants_subtitle_for'),
    'loading' => __('ui.loading_participants'),
    'loadErrorTitle' => __('ui.participants_load_error_title'),
    'loadErrorText' => __('ui.participants_load_error_text'),
    'emptySearchTitle' => __('ui.participants_empty_search_title'),
    'emptySearchText' => __('ui.participants_empty_search_text'),
    'emptyCategoryTitle' => __('ui.participants_empty_category_title'),
    'emptyCategoryText' => __('ui.participants_empty_category_text'),
    'emptyTitle' => __('ui.participants_empty_title'),
    'emptyText' => __('ui.participants_empty_text'),
    'unknown' => __('ui.unknown'),
    'statusAccepted' => __('ui.attending'),
    'statusDeclined' => __('ui.not_attending'),
    'statusPending' => __('ui.awaiting_response'),
    'statusVolunteer' => __('ui.participant_status_volunteer'),
];
@endphp
<script>window.participantsModalI18n = @json($participantsModalI18n);</script>
