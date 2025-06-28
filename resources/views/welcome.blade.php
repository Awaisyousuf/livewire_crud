<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Manager</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Trix Editor CSS -->
  
    
        <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
        <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
        
    @stack('styles')
</head>

<body>
    <div class="container py-4">
        @livewire('blog-manager')

      
   

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @push('scripts')
        <script>
            window.addEventListener('trix-set-content', event => {
                const editor = document.querySelector("trix-editor[input='content']");
                const hiddenInput = document.getElementById('content');

                if (editor && hiddenInput) {
                    hiddenInput.value = event.detail;
                    editor.editor.loadHTML(event.detail);
                }
            });
        </script>
    @endpush
    

    @livewireScripts
    
    @stack('scripts')
</body>

</html>