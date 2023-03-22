using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using System.Windows.Forms;

namespace LukaKompControlPanel
{
    static class Program
    {
        /// <summary>
        /// The main entry point for the application.
        /// </summary>
        [STAThread]
        static void Main()
        {
            Application.EnableVisualStyles();
            Application.SetCompatibleTextRenderingDefault(false);
            Application.Run(new Login());
            //Application.Run(new dodajKomponente.DodajGraficku());
            //Application.Run(new DodajKomponenteMenu());
            //Application.Run(new Dashboard(10,"admin","admin@admin.rs"));
            //Application.Run(new Privilegije());
            //Application.Run(new Konfigurator());
            //Application.Run(new potvrdaDostave());
            //Application.Run(new promenaKomponenata());
        }
    }
    
}
