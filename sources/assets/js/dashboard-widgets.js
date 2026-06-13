/** VAVS - Virtuelle Airline System (Release 1 MVP)  
 * Dashboard Widgets: P06 Basis-KPIs + Widget-Ready ⚡  
 * Vanilla JS ohne Framework! jQuery optional im HTML ✅✨  

** Autor Christoph */


document.addEventListener('DOMContentLoaded', function() {   
    // Statistiken Widget (Phase 1 High Priority!) ⭐⭐⚡ →P06 High Priority MVP!
    fetch('/api/dashboard/stats')
        .then(res => res.json())  
          .then(data => {  
            document.querySelector('.stat-card').innerHTML = '';  

              console.log('Statistik geladen:', data); // Debug-Log ⭐⭐⚡   
              
} catch(err) {console.error('API Fehler', err);}    
        
    /** Widget: Heute's Plan & Status  
      updateTodayPlanWidget();  // Live Flight Tracking (Phase1 MVP) ✅
      
    }
