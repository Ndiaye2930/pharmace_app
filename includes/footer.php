</div> <!-- Fermeture de #page-content-wrapper -->
    </div> <!-- Fermeture de #wrapper -->

    <!-- Scripts Bootstrap & FontAwesome -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

    <!-- Toggle Sidebar Script -->
    <script>
        const toggleBtn = document.getElementById("menu-toggle");
        const wrapper = document.getElementById("wrapper");

        toggleBtn.addEventListener("click", () => {
            wrapper.classList.toggle("toggled");
        });
    </script>

    <!-- Sidebar Styles -->
    <style>
        #sidebar-wrapper {
            width: 250px;
            transition: all 0.3s ease;
        }

        #page-content-wrapper {
            flex: 1;
        }

        #wrapper.toggled #sidebar-wrapper {
            margin-left: -250px;
        }

        .list-group-item.active {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }
    </style>
</body>
</html>
