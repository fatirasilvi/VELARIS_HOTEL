</div>
    
    <!-- Footer -->
    <footer class="bg-light text-center py-3 mt-5" style="margin-left: var(--sidebar-width);">
        <div class="container">
            <p class="text-muted mb-0">
                &copy; <?php echo date('Y'); ?> Velaris Hotel. All rights reserved.
            </p>
        </div>
    </footer>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    
    <!-- Highcharts JS -->
    <script src="https://code.highcharts.com/highcharts.js"></script>
    
    <!-- Custom JS -->
    <script>
        // Auto dismiss alerts after 5 seconds
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);
        
        // Toggle sidebar for mobile
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
        }
    </script>
</body>
</html>
<?php ob_end_flush();