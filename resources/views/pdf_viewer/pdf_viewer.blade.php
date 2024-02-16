<html>

<head>
    <!-- Include PDF.js styles and scripts -->
    <link rel="stylesheet" href="{{ asset('pdfjs/web/viewer.css') }}">
    <script src="{{ asset('pdfjs/build/pdf.js') }}"></script>
</head>

<body>
    <div id="pdf-viewer"></div>

    <script>
       
        const pdfPath = "{{ $pdfPath }}";
        const viewerContainer = document.getElementById('pdf-viewer');

        const pdfjsLib = window['pdfjs-dist/build/pdf'];
        pdfjsLib.GlobalWorkerOptions.workerSrc = "{{ asset('pdfjs/build/pdf.worker.js') }}";

        const loadingTask = pdfjsLib.getDocument(pdfPath);
        loadingTask.promise.then(function(pdfDocument) {
            for (let pageNum = 1; pageNum <= pdfDocument.numPages; pageNum++) {
                pdfDocument.getPage(pageNum).then(function(pdfPage) {
                    const scale = 1.5; // Adjust as needed
                    const viewport = pdfPage.getViewport({
                        scale
                    });

                    const canvas = document.createElement('canvas');
                    canvas.height = viewport.height;
                    canvas.width = viewport.width;

                    viewerContainer.appendChild(canvas);

                    const renderContext = {
                        canvasContext: canvas.getContext('2d'),
                        viewport: viewport,
                    };

                    pdfPage.render(renderContext);
                });
            }
        });
    </script>
</body>

</html>