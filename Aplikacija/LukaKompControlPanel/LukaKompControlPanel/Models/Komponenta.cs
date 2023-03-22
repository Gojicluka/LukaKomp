using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace LukaKompControlPanel.Models
{
    class Komponenta
    {
        public int id { get; set; }
        public string Ime { get; set; }
        public string opis { get; set; }
        public string slika { get; set; }
        public int proizvodjac{ get; set; }
        public int cena{ get; set; }
        public int kolicina { get; set; }
        public string tip { get; set; }
        public string atributi { get; set; }
    }
}
