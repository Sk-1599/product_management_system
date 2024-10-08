<!-- Footer -->
<!-- End of Footer -->

</div>
<!-- End of Content Wrapper -->

</div>
<!-- End of Page Wrapper -->

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <a class="btn btn-primary" href="?page=logout">Logout</a>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap core JavaScript-->
<script src="view/vendor/jquery/jquery.min.js"></script>
<script src="view/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>


<!-- Core plugin JavaScript-->
<script src="view/vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="view/js/sb-admin-2.min.js"></script>

<!-- Page level plugins -->
<script src="view/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="view/vendor/datatables/dataTables.bootstrap4.min.js"></script>

<!-- Page level custom scripts -->
<script src="js/demo/datatables-demo.js"></script>

<!-- Template Main JS File -->
<script src="assets/js/main.js"></script>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
                    document.addEventListener("DOMContentLoaded", function() {
                        const sortLinks = document.querySelectorAll(".sort");

                        sortLinks.forEach(link => {
                            link.addEventListener("click", function(e) {
                                e.preventDefault();

                                const sortField = this.getAttribute("data-sort");
                                const sortOrder = this.getAttribute("data-order");

                                fetchSortedData(sortField, sortOrder);
                            });
                        });
                    });

                    function fetchSortedData(sortField, sortOrder) {
                        const xhr = new XMLHttpRequest();
                        xhr.open("POST", "index.php?page=sortProducts", true);
                        xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
                        xhr.onreadystatechange = function() {
                            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                                document.getElementById("table").innerHTML = xhr.responseText;
                            }
                        };

                        const data = JSON.stringify({
                            sort_field: sortField,
                            sort_order: sortOrder
                        });
                        xhr.send(data);
                    }
                </script>

                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

                



</body>

</html>