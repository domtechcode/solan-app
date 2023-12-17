@push('styles')
    <style>
        html,
        body {
            height: 100%;
            padding: 0px;
            margin: 0px;
            overflow: hidden;
        }
    </style>
@endpush
<div>
    <div id="gantt_here" style='width:100%; height:100vh;'></div>
</div>

@push('scripts')
    <script type="text/javascript">
        //markers
        gantt.plugins({
            marker: true
        });
        var markers = gantt.addMarker({
            start_date: new Date(),
            css: "today",
            text: "Hari ini",


        });
        setInterval(function() {
            var today = gantt.getMarker(markers);
            today.start_date = new Date();
            gantt.updateMarker(markers);
        }, 100 * 60);
        //endmarker

        gantt.config.date_format = "%Y-%m-%d %H:%i:%s";
        gantt.config.order_branch = true;
        gantt.config.order_branch_free = true;

        gantt.config.work_time = true;
        gantt.config.duration_unit = "hour";
        gantt.config.duration_step = 1;
        gantt.config.autofit = true;
        gantt.config.scales = [{
                unit: "day",
                format: "%d %F %Y"
            },
            {
                unit: "hour",
                step: 1,
                format: "%H:%i"
            }
        ];

        gantt.setWorkTime({
            hours: [8, 12, 13, 20]
        });

        //scroll
        gantt.config.layout = {
            css: 'gantt_container',
            cols: [{
                    width: 500,
                    minWidth: 200,
                    maxWidth: 600,
                    rows: [{
                            view: 'grid',
                            scrollX: 'gridScroll',
                            scrollable: true,
                            scrollY: 'scrollVer'
                        },

                        // horizontal scrollbar for the grid
                        {
                            view: 'scrollbar',
                            id: 'gridScroll',
                            group: 'horizontal'
                        }
                    ]
                },
                {
                    resizer: true,
                    width: 1
                },
                {
                    rows: [{
                            view: 'timeline',
                            scrollX: 'scrollHor',
                            scrollY: 'scrollVer'
                        },

                        // horizontal scrollbar for the timeline
                        {
                            view: 'scrollbar',
                            id: 'scrollHor',
                            group: 'horizontal'
                        }
                    ]
                },
                {
                    view: 'scrollbar',
                    id: 'scrollVer'
                }
            ]
        };


        gantt.init("gantt_here");

        gantt.load("/api/data");

        const dp = gantt.createDataProcessor({
            url: "/api",
            mode: "REST"
        });
    </script>
@endpush
