<div>
    {{-- The best athlete wants his opponent at his best. --}}
    <div id="gantt_here" style='width:100%; height:100vh;'></div>
</div>

@push('scripts')
    <script type="text/javascript">
        gantt.plugins({
            marker: true
        });

        gantt.config.date_format = "%Y-%m-%d %H:%i:%s";
        gantt.config.order_branch = true; /*!*/
        gantt.config.order_branch_free = true; /*!*/
        gantt.plugins({
            marker: true
        });
        var dateToStr = gantt.date.date_to_str(gantt.config.task_date);
        var today = new Date();
        gantt.addMarker({
            start_date: today,
            css: "today",
            text: "Today",
            title: "Today: " + dateToStr(today)
        });
        setInterval(function() {
            var today = gantt.getMarker(id);
            today.start_date = new Date();
            today.title = date_to_str(today.start_date);
            gantt.updateMarker(id);
        }, 1000 * 60);
        gantt.init("gantt_here");
        gantt.load("/api/data");

        var dp = new gantt.dataProcessor("/api");
        dp.init(gantt);
        dp.setTransactionMode("REST");
    </script>
@endpush
