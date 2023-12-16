<div>
    <p>
        Quantity Items : <input type="text" wire:model='quantityItems'>
    </p>
    <p>
        Length Items : <input type="number" wire:model='itemsLength'>
        Width Items : <input type="number" wire:model='itemsWidth'>
    </p>
    <p>
        Length Plano : <input type="text" wire:model='planoLength'>
        Width Plano : <input type="text" wire:model='planoWidth'>
    </p>
    <p>
        Auto Rotate :
        <select type="checkbox" wire:model='orientationSheet'>
            <option label="Pilih Orientasi"></option>
            <option value="landscape">Landscape</option>
            <option value="potrait">Potrait</option>
        </select>
    </p>
    <p>
        Auto Rotate Plano :
        <select type="checkbox" wire:model='orientationPlano'>
            <option label="Pilih Orientasi"></option>
            <option value="landscape">Landscape</option>
            <option value="potrait">Potrait</option>
        </select>
    </p>
    <button wire:click='calc'>Hitung</button>
    {{-- <p>
        Hasil Sheet
    <table border="1px">
        <thead>
            <th>No</th>
            <th>col</th>
            <th>row</th>
            <th>Length</th>
            <th>Width</th>
            <th>Length Waste</th>
            <th>Width Waste</th>
            <th>Orientation</th>
            <th>Items Per Sheet</th>
        </thead>
        <tbody>
            @foreach ($resultSheet as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item['col'] }}</td>
                    <td>{{ $item['row'] }}</td>
                    <td>{{ $item['sheetLength'] }}</td>
                    <td>{{ $item['sheetWidth'] }}</td>
                    <td>{{ $item['wasteLength'] }}</td>
                    <td>{{ $item['wasteWidth'] }}</td>
                    <td>{{ $item['orientationSheet'] }}</td>
                    <td>{{ $item['itemsPerSheet'] }}</td>
                </tr>
            @endforeach

        </tbody>
    </table>
    </p>

    Maksimal item on plano : {{ $maxItemsOnPlano }}
    <p>
        Hasil Plano
    <table border="1px">
        <thead>
            <th>No</th>
            <th>col</th>
            <th>row</th>
            <th>Length Sheet</th>
            <th>Width Sheet</th>
            <th>Length Cut Sheet</th>
            <th>Width Cut Sheet</th>
            <th>Orientation</th>
            <th>Total Items</th>
            <th>Length Waste</th>
            <th>Width Waste</th>
            <th>wasteLengthPlano_1</th>
            <th>wasteWidthPlano_1</th>
            <th>wasteLengthPlano_2</th>
            <th>wasteWidthPlano_2</th>
        </thead>
        <tbody>
            @foreach ($resultPlano as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item['col'] }}</td>
                    <td>{{ $item['row'] }}</td>
                    <td>{{ $item['sheetLength'] }}</td>
                    <td>{{ $item['sheetWidth'] }}</td>
                    <td>{{ $item['cutSheetLength'] }}</td>
                    <td>{{ $item['cutSheetWidth'] }}</td>
                    <td>{{ $item['orientationPlano'] }}</td>
                    <td>{{ $item['itemsPerPlano'] }}</td>
                    <td>{{ $item['wasteCutLength'] }}</td>
                    <td>{{ $item['wasteCutWidth'] }}</td>
                    <td>{{ $item['wasteLengthPlano_1'] }}</td>
                    <td>{{ $item['wasteWidthPlano_1'] }}</td>
                    <td>{{ $item['wasteLengthPlano_2'] }}</td>
                    <td>{{ $item['wasteWidthPlano_2'] }}</td>
                </tr>
            @endforeach

        </tbody>
    </table>
    </p> --}}

    <p>Gambar Setting</p>
    <div class="fabric-canvas-wrapper-setting" style="border: solid 1px red;">
        <canvas id="canvasSetting"></canvas>
    </div>
    <p>Gambar Bahan</p>
    <div class="fabric-canvas-wrapper-bahan" style="border: solid 1px red;">
        <canvas id="canvasBahan"></canvas>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('createLayoutSetting', (data) => {
                let col = data[0].col;
                let row = data[0].row;
                let sheetLength = data[0].sheetLength;
                let sheetWidth = data[0].sheetWidth;
                let wasteLength = data[0].wasteLength;
                let wasteWidth = data[0].wasteWidth;
                let itemsWidth = data[0].itemsWidth;
                let itemsLength = data[0].itemsLength;
                let gapBetweenItems = data[0].gapBetweenItems;
                let sheetMarginTop = data[0].sheetMarginTop;
                let sheetMarginBottom = data[0].sheetMarginBottom;
                let sheetMarginLeft = data[0].sheetMarginLeft;
                let sheetMarginRight = data[0].sheetMarginRight;
                let orientationSheet = data[0].orientationSheet;

                function resizeCanvas() {
                    const outerCanvasContainer = $('.fabric-canvas-wrapper-setting')[0];
                    console.log(outerCanvasContainer);
                    const ratio = canvas.getWidth() / canvas.getHeight();
                    const containerWidth = outerCanvasContainer.clientWidth;
                    const containerHeight = outerCanvasContainer.clientHeight;

                    const scale = containerWidth / canvas.getWidth();
                    const zoom = canvas.getZoom() * scale;
                    canvas.setDimensions({
                        width: containerWidth,
                        height: containerWidth / ratio
                    });
                    canvas.setViewportTransform([zoom, 0, 0, zoom, 0, 0]);
                }
                $(window).resize(resizeCanvas);

                var canvas = new fabric.Canvas('canvasSetting');
                let canvasWidth = sheetLength + 2;
                let canvasHeight = sheetWidth + 2;

                canvas.setWidth(canvasWidth);
                canvas.setHeight(canvasHeight);

                // Buat objek persegi panjang sesuai dengan data yang diterima
                var rectangle = new fabric.Rect({
                    top: 1,
                    left: 0,
                    width: sheetLength,
                    height: sheetWidth,
                    fill: 'transparent',
                    stroke: 'red',
                    strokeWidth: 0.1,
                    strokeUniform: true
                });

                canvas.add(rectangle);

                var textSheetLength = new fabric.IText('Panjang Lembar Cetak = ' + (sheetLength) + ' cm', {
                    fontFamily: 'Arial',
                    fontSize: 0.5,
                    left: 0, // Posisi horizontal di tengah
                    top: -0.2, // Posisi vertikal di tengah
                });

                canvas.add(textSheetLength);

                var textSheetWidth = new fabric.IText('Lebar Lembar Cetak = ' + (sheetWidth) + ' cm', {
                    fontFamily: 'Arial',
                    fontSize: 0.5,
                    left: sheetLength + 1.5, // Posisi horizontal di tengah
                    top: 0.2, // Posisi vertikal di tengah
                    angle: 90,
                });

                canvas.add(textSheetWidth);

                // Loop untuk baris
                for (let i = 0; i < row; i++) {
                    // Loop untuk kolom
                    for (let j = 0; j < col; j++) {
                        // Hitung posisi kiri dan atas untuk setiap persegi panjang
                        var leftPos = j * (itemsLength + gapBetweenItems) + sheetMarginLeft;
                        var topPos = i * (itemsWidth + gapBetweenItems) + sheetMarginTop + 1;

                        // Buat objek persegi panjang
                        var rectangle = new fabric.Rect({
                            top: topPos,
                            left: leftPos,
                            width: itemsLength,
                            height: itemsWidth,
                            fill: 'transparent',
                            stroke: 'red',
                            strokeWidth: 0.1,
                            strokeUniform: true
                        });

                        // Tambahkan objek persegi panjang ke dalam canvas
                        canvas.add(rectangle);

                        // Hitung posisi teks untuk menempatkannya di tengah kotak
                        var textLeftPos = leftPos + itemsLength / 2;
                        var textTopPos = topPos + itemsWidth / 2;

                        // Tambahkan teks panjang ke dalam canvas
                        var textLength = new fabric.IText(itemsLength + ' cm', {
                            fontFamily: 'Arial',
                            fontSize: 0.5,
                            left: leftPos,
                            top: topPos,
                            textAlign: 'center',
                        });

                        canvas.add(textLength);

                        // Tambahkan teks lebar ke dalam canvas
                        var textWidth = new fabric.IText(itemsWidth + ' cm', {
                            fontFamily: 'Arial',
                            fontSize: 0.5,
                            left: leftPos,
                            top: topPos + itemsWidth,
                            angle: -90,
                            textAlign: 'center',
                        });

                        canvas.add(textWidth);
                    }
                }

                resizeCanvas();
            });

            Livewire.on('createLayoutBahan', (data) => {
                let col = data[0].col;
                let row = data[0].row;
                let planoLength = data[0].planoLength;
                let planoWidth = data[0].planoWidth;
                let sheetLength = data[0].sheetLength;
                let sheetWidth = data[0].sheetWidth;
                let wasteLength = data[0].wasteLength;
                let wasteWidth = data[0].wasteWidth;
                let itemsWidth = data[0].itemsWidth;
                let itemsLength = data[0].itemsLength;
                let gapBetweenItems = data[0].gapBetweenItems;
                let sheetMarginTop = data[0].sheetMarginTop;
                let sheetMarginBottom = data[0].sheetMarginBottom;
                let sheetMarginLeft = data[0].sheetMarginLeft;
                let sheetMarginRight = data[0].sheetMarginRight;
                let orientationSheet = data[0].orientationSheet;

                function resizeCanvas() {
                    const outerCanvasContainer = $('.fabric-canvas-wrapper-bahan')[0];
                    console.log(outerCanvasContainer);
                    const ratio = canvas.getWidth() / canvas.getHeight();
                    const containerWidth = outerCanvasContainer.clientWidth;
                    const containerHeight = outerCanvasContainer.clientHeight;

                    const scale = containerWidth / canvas.getWidth();
                    const zoom = canvas.getZoom() * scale;
                    canvas.setDimensions({
                        width: containerWidth,
                        height: containerWidth / ratio
                    });
                    canvas.setViewportTransform([zoom, 0, 0, zoom, 0, 0]);
                }

                $(window).resize(resizeCanvas);

                var canvas = new fabric.Canvas('canvasBahan');
                let canvasWidth = planoLength + 2.5;
                let canvasHeight = planoWidth + 2.5;

                canvas.setWidth(canvasWidth);
                canvas.setHeight(canvasHeight);

                // Buat objek persegi panjang sesuai dengan data yang diterima
                var rectangle = new fabric.Rect({
                    top: 2,
                    left: 0,
                    width: planoLength,
                    height: planoWidth,
                    fill: 'transparent',
                    stroke: 'red',
                    strokeWidth: 0.1,
                    strokeUniform: true
                });

                canvas.add(rectangle);

                var textPlanoLength = new fabric.IText('Panjang Bahan = ' + (planoLength) + ' cm', {
                    fontFamily: 'Arial',
                    fontSize: 1,
                    left: 0, // Posisi horizontal di tengah
                    top: -0.2, // Posisi vertikal di tengah
                });

                canvas.add(textPlanoLength);

                var textPlanoWidth = new fabric.IText('Lebar Bahan = ' + (planoWidth) + ' cm', {
                    fontFamily: 'Arial',
                    fontSize: 1,
                    left: planoLength + 2, // Posisi horizontal di tengah
                    top: 0.2, // Posisi vertikal di tengah
                    angle: 90,
                });

                canvas.add(textPlanoWidth);

                // Loop untuk baris
                for (let i = 0; i < row; i++) {
                    // Loop untuk kolom
                    for (let j = 0; j < col; j++) {
                        // Hitung posisi kiri dan atas untuk setiap persegi panjang
                        var leftPos = j * sheetLength;
                        var topPos = i * sheetWidth + 2;

                        // Buat objek persegi panjang
                        var rectangle = new fabric.Rect({
                            top: topPos,
                            left: leftPos,
                            width: sheetLength,
                            height: sheetWidth,
                            fill: 'transparent',
                            stroke: 'red',
                            strokeWidth: 0.1,
                            strokeUniform: true
                        });

                        // Tambahkan objek persegi panjang ke dalam canvas
                        canvas.add(rectangle);

                        // Hitung posisi teks untuk menempatkannya di tengah kotak
                        var textLeftPos = leftPos + sheetLength / 2;
                        var textTopPos = topPos + sheetWidth / 2;

                        // Tambahkan teks panjang ke dalam canvas
                        var textLength = new fabric.IText(sheetLength + ' cm', {
                            fontFamily: 'Arial',
                            fontSize: 1,
                            left: leftPos,
                            top: topPos,
                            textAlign: 'center',
                        });

                        canvas.add(textLength);

                        // Tambahkan teks lebar ke dalam canvas
                        var textWidth = new fabric.IText(sheetWidth + ' cm', {
                            fontFamily: 'Arial',
                            fontSize: 1,
                            left: leftPos,
                            top: topPos + sheetWidth,
                            angle: -90,
                            textAlign: 'center',
                        });

                        canvas.add(textWidth);
                    }
                }
                resizeCanvas();
            });
        });
    </script>
@endpush
