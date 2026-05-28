<footer class="mptb-footer">
    <div class="footer-left">
        PT. MITRA PELANGI TERANGI BANGSA
    </div>
    <div class="footer-right">
        <span class="footer-version">
             © {{ date('Y') }} IT Support MPTB
        </span>
    </div>

    <div class="footer-center">
       v{{ config('app.version') }}
    </div>
</footer>

<style>

/* Footer utama */
.mptb-footer{
    width:100%;
    min-height:55px;

    display:flex;
    align-items:center;
    justify-content:space-between;

    padding:10px 25px;

    background:#ffffff;
    border-top:1px solid #dee2e6;
    
    color:#6c757d;
    font-size:14px;

    box-sizing:border-box;
}

/* kiri */
.footer-left{
    display:flex;
    align-items:center;
    gap:10px;
}

/* badge versi */
.footer-version{
    background:#17a2b8;
    color:white;

    padding:3px 10px;

    border-radius:20px;

    font-size:12px;
    font-weight:600;
}

/* tengah */
.footer-center{
    text-align:center;
}

/* kanan */
.footer-right{
    text-align:right;
}

/* Tablet */
@media(max-width:768px){

    .mptb-footer{
        flex-direction:column;

        gap:5px;

        text-align:center;

        padding:15px 10px;

        font-size:13px;
    }

    .footer-left{
        justify-content:center;
    }

    .footer-right{
        text-align:center;
    }
}

/* HP kecil */
@media(max-width:480px){

    .mptb-footer{
        font-size:12px;
    }

    .footer-version{
        font-size:11px;
        padding:2px 8px;
    }
}
</style>