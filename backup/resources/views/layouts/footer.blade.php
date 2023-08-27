<!----- THIS FORM USE FOR DELETE  ---->
<form method="DELETE" id="form-del">
    <input name="_method" type="hidden" value="DELETE">
    {{ csrf_field() }}

</form>
<!----- THIS FORM USE FOR DELETE  ---->

<footer class="footer-section">
    <div class="footer clearfix mb-0 text-muted">
        <div class="float-start">

        </div>
        <div class="float-end">
            <p>
                <script>
                    document.write(new Date().getFullYear())
                </script> &copy; eBroker
            </p>
        </div>
    </div>
</footer>
