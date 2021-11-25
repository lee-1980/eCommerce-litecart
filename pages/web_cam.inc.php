<style>
    /* In order to place the tracking correctly */
    #livestream_scanner {
        width: 100%;
        height: auto;
    }
</style>
<link rel="stylesheet" rel="preload" as="style" onload="this.rel='stylesheet';this.onload=null" href="https://fonts.googleapis.com/css?family=Roboto:300,300italic,700,700italic">
<link rel="stylesheet" rel="preload" as="style" onload="this.rel='stylesheet';this.onload=null" href="https://unpkg.com/normalize.css@8.0.0/normalize.css">
<link rel="stylesheet" rel="preload" as="style" onload="this.rel='stylesheet';this.onload=null" href="https://unpkg.com/milligram@1.3.0/dist/milligram.min.css">
<div class="modal" id="livestream_scanner">
    <div>
        <a class="button" id="startButton">Start</a>
        <a class="button" id="resetButton">Reset</a>
    </div>
    <div style = "position: relative;">
        <div style="
    position: absolute;
    border-top: 1px solid yellowgreen;
    width: 70%;
    height: auto;
    top: 50%;
    left: 50%;
    -webkit-transform: translateX(-50%);
    transform: translateX(-50%);
    animation: flasher_red 2s linear infinite;
"></div>
        <video id="video" width="100%"  style="border: 1px solid gray"></video>
    </div>

    <div id="sourceSelectPanel" >
        <label for="sourceSelect">Change video source:</label>
        <select id="sourceSelect" style="max-width:400px">
        </select>
    </div>
    <div>Result:<span id="barcode_result"></span></div>

</div>

<script type="text/javascript">
    $(function () {
        let selectedDeviceId;
    
        const codeReader = new ZXing.BrowserBarcodeReader();
        console.log('ZXing code reader initialized');
        codeReader.getVideoInputDevices()
            .then((videoInputDevices) => {
                const sourceSelect = document.getElementById('sourceSelect');
                console.log(videoInputDevices.length);
                if (videoInputDevices.length >= 1) {
                    videoInputDevices.forEach((element) => {
                        const sourceOption = document.createElement('option');
                        sourceOption.text = element.label;
                        sourceOption.value = element.deviceId;
                        if(element.label.indexOf('back') != -1 || element.label.indexOf('rear') != -1 || element.label.indexOf('environment') != -1){
                            sourceOption.selected = 'true';
                            selectedDeviceId = element.deviceId;
                        }
                        sourceSelect.appendChild(sourceOption);
                    });

                    sourceSelect.onchange = () => {
                        selectedDeviceId = sourceSelect.value;
                    };

                    const sourceSelectPanel = document.getElementById('sourceSelectPanel');
                    sourceSelectPanel.style.display = 'block'
                }

                document.getElementById('startButton').addEventListener('click', () => {
                    codeReader.decodeOnceFromVideoDevice(selectedDeviceId, 'video').then((result) => {
                        console.log(result);
                        document.getElementById('barcode_result').textContent = result.text;
                        codeReader.reset();
                        $.featherlight.close();
                        $('#search input[name="query"]').val(result.text);
                        $('#search input[name="query"]').closest('form').submit();

                    }).catch((err) => {
                        console.error(err)
                        document.getElementById('barcode_result').textContent = err
                    });
                    console.log(`Started continous decode from camera with id ${selectedDeviceId}`)
                });
                document.getElementById('resetButton').addEventListener('click', () => {
                        document.getElementById('barcode_result').textContent = '';
                        codeReader.reset();
                        console.log('Reset.')
                    });

            })
            .catch((err) => {
                console.error(err)
            });
    });

</script>