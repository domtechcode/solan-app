@push('styles')
    <style>
        .gantt_cal_chosen,
        .gantt_cal_chosen select {
            width: 400px;
        }

        .owner-label {
            width: 20px;
            height: 20px;
            line-height: 20px;
            font-size: 12px;
            display: inline-block;
            border: 1px solid #cccccc;
            border-radius: 25px;
            background: #e6e6e6;
            color: #6f6f6f;
            font-weight: bold;
            margin-top: 5px;
        }
    </style>
@endpush
<div>
    <div id="gantt_here" style='width:100%; height:100vh;'></div>
</div>

@push('scripts')
    <script>
        gantt.form_blocks["multiselect"] = {
            render: function(sns) {
                var height = (sns.height || "23") + "px";
                var html = "<div class='gantt_cal_ltext gantt_cal_chosen gantt_cal_multiselect' style='height:" +
                    height + ";'><select data-placeholder='...' class='chosen-select' multiple>";
                if (sns.options) {
                    for (var i = 0; i < sns.options.length; i++) {
                        if (sns.unassigned_value !== undefined && sns.options[i].key == sns.unassigned_value) {
                            continue;
                        }
                        html += "<option value='" + sns.options[i].key + "'>" + sns.options[i].label + "</option>";
                    }
                }
                html += "</select></div>";
                return html;
            },

            set_value: function(node, value, ev, sns) {
                node.style.overflow = "visible";
                node.parentNode.style.overflow = "visible";
                node.style.display = "inline-block";
                var select = $(node.firstChild);

                if (value) {
                    value = (value + "").split(",");
                    select.val(value);
                } else {
                    select.val([]);
                }

                select.chosen();
                if (sns.onchange) {
                    select.change(function() {
                        sns.onchange.call(this);
                    })
                }
                select.trigger('chosen:updated');
                select.trigger("change");
            },

            get_value: function(node, ev) {
                var value = $(node.firstChild).val();
                return value;
            },

            focus: function(node) {
                $(node.firstChild).focus();
            }
        };

        gantt.serverList("people", [{
                key: 1,
                label: "Lia"
            },
            {
                key: 2,
                label: "Asep"
            },
            {
                key: 3,
                label: "Dedi"
            },
            {
                key: 4,
                label: "Risti"
            },
            {
                key: 5,
                label: "Adit"
            }
        ]);

        function findUser(id) {
            var list = gantt.serverList("people");
            for (var i = 0; i < list.length; i++) {
                if (list[i].key == id) {
                    return list[i];
                }
            }
            return null;
        }

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

        gantt.config.scale_height = 50;
        gantt.config.scales = [{
                unit: "day",
                step: 1,
                format: "%j, %D",
            },
            {
                unit: "month",
                step: 1,
                format: "%F, %Y"
            },
        ];

        //colomns
        gantt.config.reorder_grid_columns = true;
        gantt.config.columns = [{
                name: "text",
                align: "left",
                tree: true,
                width: "*",
                min_width: 200,
                resize: true
            },
            {
                name: "lembar_cetak",
                label: "Target LC",
                align: "center",
                width: "*",
                resize: true
            },
            {
                name: "start_date",
                align: "center",
                min_width: 100,
                resize: true
            },
            {
                name: "end_date",
                label: "End",
                align: "center",
                min_width: 100,
                template: function(task) {
                    return gantt.templates.date_grid(task.end_date, task);
                },
                resize: true
            },
            {
                name: "duration",
                align: "center",
                width: 80,
                resize: true
            },
            {
                name: "owner",
                align: "center",
                width: 120,
                label: "Owner",
                template: function(task) {
                    if (task.type == gantt.config.types.project) {
                        return "";
                    }

                    var result = "";

                    var owners = task.owners;

                    if (!owners || !owners.length) {
                        return "Unassigned";
                    }

                    if (owners.length == 1) {
                        return findUser(owners[0]).label;
                    }

                    owners.forEach(function(ownerId) {
                        var owner = findUser(ownerId);
                        if (!owner)
                            return;
                        result += "<div class='owner-label' title='" + owner.label + "'>" + owner.label
                            .substr(0, 1) + "</div>";

                    });

                    return result;
                },
                resize: true
            },
            {
                name: "machine",
                label: "Machine",
                align: "center",
                width: 80,
                resize: true
            },
            {
                name: "priority",
                label: "Priority",
                align: "center",
                width: 80,
                resize: true
            },
            {
                name: "type",
                label: "Type",
                align: "center",
                width: 80,
                resize: true
            },
            {
                name: "add",
                width: 44
            }
        ];

        gantt.config.layout = {
            css: "gantt_container",
            cols: [{
                    width: 700,
                    min_width: 300,
                    rows: [{
                            view: "grid",
                            scrollX: "gridScroll",
                            scrollable: true,
                            scrollY: "scrollVer"
                        },
                        {
                            view: "scrollbar",
                            id: "gridScroll",
                            group: "horizontal"
                        }
                    ]
                },
                {
                    resizer: true,
                    width: 1
                },
                {
                    rows: [{
                            view: "timeline",
                            scrollX: "scrollHor",
                            scrollY: "scrollVer"
                        },
                        {
                            view: "scrollbar",
                            id: "scrollHor",
                            group: "horizontal"
                        }
                    ]
                },
                {
                    view: "scrollbar",
                    id: "scrollVer"
                }
            ]
        };

        gantt.attachEvent("onParse", function() {
            gantt.eachTask(function(task) {
                // fill 'task.user' field with random data
                task.user = Math.round(Math.random() * 3);
                //
                if (gantt.hasChild(task.id))
                    task.type = gantt.config.types.project
            });
        });

        gantt.locale.labels.section_owner = "Owner";
        gantt.config.lightbox.sections = [{
                name: "description",
                height: 38,
                map_to: "text",
                type: "textarea",
                focus: true
            },
            {
                name: "owner",
                height: 60,
                type: "multiselect",
                options: gantt.serverList("people"),
                map_to: "owners"
            },
            {
                name: "time",
                type: "duration",
                map_to: "auto"
            }
        ];

        gantt.init("gantt_here");

        gantt.parse({
            data: [{
                    id: 1,
                    text: "Hangtag Project",
                    lembar_cetak: "1000",
                    start_date: "01-12-2023",
                    duration: 29,
                    progress: 0.4,
                    type: "project",
                    priority: "High",
                    machine: "-",
                    open: true
                },
                {
                    id: 2,
                    text: "Setting",
                    lembar_cetak: "-",
                    start_date: "02-12-2023",
                    duration: 8,
                    progress: 0.6,
                    type: "task",
                    priority: "High",
                    machine: "-",
                    parent: 1
                },
                {
                    id: 3,
                    text: "Checker",
                    lembar_cetak: "-",
                    start_date: "03-12-2023",
                    duration: 8,
                    progress: 0.6,
                    type: "task",
                    priority: "High",
                    machine: "-",
                    parent: 1
                },
                {
                    id: 4,
                    text: "Plate",
                    lembar_cetak: "-",
                    start_date: "04-12-2023",
                    duration: 8,
                    progress: 0.6,
                    type: "task",
                    priority: "High",
                    machine: "-",
                    parent: 1
                }
            ]
        });
    </script>
@endpush
