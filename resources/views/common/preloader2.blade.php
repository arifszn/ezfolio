<div id="szn-preloader">
    <style>
        #szn-preloader {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 9999;
            overflow: hidden;
            background: #fff;
        }

        #szn-preloader:before {
            content: "";
            position: fixed;
            top: calc(50% - 30px);
            left: calc(50% - 30px);
            border: 6px solid var(--z-accent-color);
            border-top-color: #fff;
            border-bottom-color: #fff;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            -webkit-animation: animate-preloader 1s linear infinite;
            animation: animate-preloader 1s linear infinite;
        }

        
        @-webkit-keyframes animate-preloader {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }

        @keyframes animate-preloader {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }
    </style>

</div>