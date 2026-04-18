  $(document).ready(function() {
            // Array nama hari dan bulan dalam Bahasa Indonesia
            const hariIndo = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
            const bulanIndo = ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agt", "Sep", "Okt", "Nov", "Des"];

            function updateDateTime() {
                const now = new Date();
                
                // Format hari
                const hari = hariIndo[now.getDay()];
                
                // Format tanggal (dd)
                const tanggal = String(now.getDate()).padStart(2, '0');
                
                // Format bulan (MMM)
                const bulan = bulanIndo[now.getMonth()];
                
                // Format tahun (yyyy)
                const tahun = now.getFullYear();
                
                // Format jam:menit:detik (2 digit)
                const jam = String(now.getHours()).padStart(2, '0');
                const menit = String(now.getMinutes()).padStart(2, '0');
                const detik = String(now.getSeconds()).padStart(2, '0');
                
                // Gabungkan string sesuai format
                const formattedDateTime = `${hari}, ${tanggal}-${bulan}-${tahun} ${jam}:${menit}:${detik}`;
                
                // Masukkan ke elemen dengan id "current-date-time"
                $("#current-date-time").text(formattedDateTime);
            }
            
            // Panggil pertama kali
            updateDateTime();
            
            // Update setiap detik (1000 ms)
            setInterval(updateDateTime, 1000);
        });