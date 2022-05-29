<div>
    <div class="imagen_ini bg-cover bg-no-repeat bg-center fixed px-8 py-2">
        <div class="flex  h-full flex-col justify-between  items-start">
            <button class="  bg-red-600
                    hover:bg-red-800 
                    hover:text-white
                    text-white
                    font-bold 
                    h-12
                    w-40
                    px-4 
                    mt-12
                    rounded" 
                onclick="window.open('http://markka.com.co/portal' , 'Pedidos' , 'location=0' )"
            >
                PEDIDOS
            </button>

            <div class="h-16
                bg-gradient-to-r 
                from-red-700 
                via-green-600  
                to-blue-500  
                font-extrabold 
                text-7xl
                bg-clip-text 
                self-center
                text-transparent   ">
                MARKKA
            </div>

            <button class="bg-blue-500 
                hover:bg-blue-700
                text-white
                font-bold 
                h-12
                px-4 
                self-end
                mb-12
                rounded" 
                onclick="window.open('{{route('login')}}' , 'Contabilidad' , 'location=0' )"
            >
            RECAUDOS
            </button>

        </div>
    </div>
</div>
