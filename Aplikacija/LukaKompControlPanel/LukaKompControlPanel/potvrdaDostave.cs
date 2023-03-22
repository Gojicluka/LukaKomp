using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;

using LukaKompControlPanel.Models;
using LukaKompControlPanel.Klase;

namespace LukaKompControlPanel
{
    public partial class potvrdaDostave : Form
    {
        List<racun> racuni = new List<racun>();
        public potvrdaDostave()
        {
            InitializeComponent();
        }

        private void potvrdaDostave_Load(object sender, EventArgs e)
        {
            racuni = dataAccess.LoadData<racun, dynamic>(
                @"SELECT r.id as id ,r.dostavljeno 
                    as dostavljeno ,uc.ukupnacena as ukCena, r.datum_naruceno as datum_naruceno, r.datum_dostavljeno as datum_dostavljeno, korisnik.username as username
                    FROM racun as r
                    cross join (
                        SELECT SUM(k.cena*rm.kolicina) as ukupnacena, r.id as id FROM racun as r
                        inner join racun_medjutabela as rm on r.id = rm.idracun
                        inner join komponente as k on rm.idDrugeTabele = k.id 
                        group by r.id
                    ) as uc on r.id = uc.id
                    inner join racun_medjutabela as rm on r.id = rm.idracun
                    inner join komponente as k on rm.idDrugeTabele = k.id 
                    inner join korisnik on korisnik.id = r.korisnik_id
                    group by r.id",
                new { },
                Helper.CnnVal("LukaKomp"));

            for (int i = 0; i < racuni.Count; i++)
            {
                var index = dataGridView1.Rows.Add();
                dataGridView1.Rows[index].Cells["id"].Value = racuni[i].id;
                dataGridView1.Rows[index].Cells["korisnik"].Value = racuni[i].username;
                dataGridView1.Rows[index].Cells["datum_naruceno"].Value = racuni[i].datum_naruceno;
                dataGridView1.Rows[index].Cells["datum_dostavljeno"].Value = racuni[i].datum_dostavljeno;
                dataGridView1.Rows[index].Cells["ukupna_cena"].Value = racuni[i].ukCena;

                if (racuni[i].dostavljeno == 1) { 
                    dataGridView1.Rows[index].Cells["dostavljeno"].Value = true;
                    dataGridView1.Rows[index].Cells["dostavljeno"].ReadOnly = true; 
                }
                else dataGridView1.Rows[index].Cells["dostavljeno"].Value = false;
              
            }
            foreach (Control c in this.Controls)
            {
                c.Font = new Font(Design.fonts.Families[0], 10f, FontStyle.Regular);
            }
           
        }

        private void dataGridView1_CellContentClick(object sender, DataGridViewCellEventArgs e)
        {

        }

        private void button1_Click(object sender, EventArgs e)
        {
            string idovi = "";
            for(int i=0;i<dataGridView1.Rows.Count;i++)
            {
                if (Convert.ToBoolean(dataGridView1.Rows[i].Cells[5].Value) && !dataGridView1.Rows[i].Cells[5].ReadOnly)
                {
                    if (idovi != "") idovi += ",";
                    idovi+= dataGridView1.Rows[i].Cells[0].Value;
                }
            }
            if(idovi!="")
            {
                dataAccess.SaveData<dynamic>($"UPDATE racun SET dostavljeno=1,datum_dostavljeno=now() WHERE id in({idovi})"
                    , new { }, Helper.CnnVal("LukaKomp"));
                MessageBox.Show("Uspesno!");
                
            }
            this.Close();
        }

        private void exitButton_Click(object sender, EventArgs e)
        {
            this.Close();
        }
        Point lastPoint;
        private void potvrdaDostave_MouseMove(object sender, MouseEventArgs e)
        {
            if (e.Button == MouseButtons.Left)
            {
                this.Left += e.X - lastPoint.X;
                this.Top += e.Y - lastPoint.Y;
            }
        }

        private void potvrdaDostave_MouseDown(object sender, MouseEventArgs e)
        {
            lastPoint = new Point(e.X, e.Y);
        }
    }
}
