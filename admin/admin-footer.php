</div> 

    <footer style="
        margin-left: 280px; 
        padding: 30px; 
        text-align: center; 
        color: var(--text-muted); 
        font-size: 0.85rem;
        background: transparent;
    ">
        <div style="
            border-top: 1px solid rgba(0, 0, 0, 0.05); 
            padding-top: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
        ">
            <p>&copy; <?php echo date("Y"); ?> <strong>QuizHub Pro</strong>. All rights reserved.</p>
            <p>
                <span style="margin-right: 15px;">Version 2.0.1</span>
                <i class="fas fa-circle" style="font-size: 8px; color: #2ecc71; margin-right: 5px;"></i> System Online
            </p>
        </div>
    </footer>

    <script>
        // Smooth fade-in for table rows
        document.querySelectorAll('.table tr').forEach((row, index) => {
            row.style.opacity = '0';
            row.style.transform = 'translateY(10px)';
            row.style.transition = 'all 0.4s ease';
            setTimeout(() => {
                row.style.opacity = '1';
                row.style.transform = 'translateY(0)';
            }, index * 50);
        });

        // Auto-hide alerts after 3 seconds
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            }, 3000);
        });
    </script>
</body>
</html>