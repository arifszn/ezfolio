<img id='pixel-tracking' style='display: none !important;' src=""/>
<script>
    //place it after jquery loaded
    $(function() {
        trackingId = null;
        if (!localStorage.getItem("pixel-tracking")) {
            trackingId = '{{\Str::random(30)}}';
            localStorage.setItem("pixel-tracking", trackingId);
        } else {
            trackingId = localStorage.getItem("pixel-tracking");
        }
        $('#pixel-tracking').attr("src", "{{ route('pixel-tracker') }}?event=page_visit&tracking_id="+trackingId);         
    });
</script>