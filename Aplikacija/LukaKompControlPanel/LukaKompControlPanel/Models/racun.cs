using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace LukaKompControlPanel.Models
{
    class racun
    {
        public int id { get; set; }
        public int korisnik_id { get; set; }
        public int dostavljeno { get; set; }
        public DateTime datum_naruceno { get; set; }
        public DateTime datum_dostavljeno { get; set; }
        public int ukProdato { get; set; }
        public int ukCena { get; set; }
        public string username { get; set; }
    }
}
