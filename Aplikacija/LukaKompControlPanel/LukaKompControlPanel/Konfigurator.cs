using LukaKompControlPanel.Klase;
using LukaKompControlPanel.Models;
using System;
using System.Collections.Generic;
using System.Drawing;
using System.Windows.Forms;

namespace LukaKompControlPanel
{
    public partial class Konfigurator : Form
    {
        private List<Komponenta> listaKomponenata = new List<Komponenta>();
        private List<ComboBox> comboBoxevi = new List<ComboBox>();
        private List<Label> labeli = new List<Label>();
        private List<TextBox> textBoxevi = new List<TextBox>();

        private int[] nizSelektovanih = new int[7];

        private List<ComboboxItem> listaObrisanihMaticnih = new List<ComboboxItem>();

        private string[] tipovi = new string[] { "procesori", "maticne", "graficke", "kuciste", "disk", "napajanje", "ram" };

        public Konfigurator()
        {
            InitializeComponent();
        }

        private void submitButton_Click(object sender, EventArgs e)
        {
            if (textBoxIme.Text != "")
            {
                

                IDictionary<string, object> unosDictionary = new System.Dynamic.ExpandoObject();

                string ime = textBoxIme.Text;
                string naredba = "";

                for (int i = 0; i < 7; i++)
                {
                    if (i != 0) naredba += ",";
                    //Ukoliko su nepravilne kolicine stavljamo samo da je jedna kolicina
                    if (textBoxevi[i].Text == "0" || textBoxevi[i].Text == "") textBoxevi[i].Text = "1";
                    if (String.IsNullOrWhiteSpace(nizSelektovanih[i].ToString()) || nizSelektovanih[i] == -1)
                    {
                        MessageBox.Show("Neke stavke nisu unete " + i);
                        return;
                    }

                    naredba += $"(@ime{i},@idkomponente{i},@kolcina{i})";
                    unosDictionary[$"ime{i}"] = ime;
                    unosDictionary[$"idkomponente{i}"] = listaKomponenata[nizSelektovanih[i]].id;
                    unosDictionary[$"kolcina{i}"] = textBoxevi[i].Text;
                }
                
                dynamic unos = unosDictionary;
              
                
                dataAccess.SaveData<dynamic>($"Insert into konfiguracije (ime,idKomponente,kolicina) values {naredba}"
                    , unos, Helper.CnnVal("LukaKomp"));
                
                MessageBox.Show("Sacuvano");
                this.Close();
            }
        }

        private async void Konfigurator_Load(object sender, EventArgs e)
        {
            //loadujemo Sve komponente
            listaKomponenata = await dataAccess.LoadDataAsync<Komponenta, dynamic>
                ("select * from komponente order by tip", new { }, Helper.CnnVal("LukaKomp"));

            //Dodajemo kontrole
            int top = 100, left = 15;
            for (int i = 0; i < tipovi.Length; i++)
            {
                napraviLabel(top, left, tipovi[i], i);
                top += 20;
                napraviComboBox(top, left, i, tipovi[i]);
                napraviTextBox(top, left + 293, tipovi[i], i);
                top += 25;
            }

            for (int i = 0; i < listaKomponenata.Count; i++)
            {
                int trenutniIndex = Array.IndexOf(tipovi, listaKomponenata[i].tip);
                ComboboxItem item = new ComboboxItem();
                item.Value = i;
                item.Text = listaKomponenata[i].Ime;
                comboBoxevi[trenutniIndex].Items.Add(item);
            }
            labeli[0].Show();
            comboBoxevi[0].Show();
            textBoxevi[0].Show();

            submitButton.Hide();

            foreach (Control c in this.Controls)
            {
                c.Font = new Font(Design.fonts.Families[0], 12f, FontStyle.Regular);
            }
            label1.Font = new Font(Design.fonts.Families[0], 30);
        }

        public void selektovano(object sender, EventArgs e, int comboBoxId, string tip)
        {
            //MessageBox.Show((comboBoxevi[comboBoxId].SelectedItem as ComboboxItem).Value.ToString());
            if (comboBoxevi[comboBoxId].Text != "")
            {
                int selektovanId = Int32.Parse((comboBoxevi[comboBoxId].SelectedItem as ComboboxItem).Value.ToString());
                int indexTipa = Array.IndexOf(tipovi, tip);

                if (comboBoxId != 6)
                {
                    for (int i = indexTipa + 1; i < tipovi.Length; i++)
                    {
                        nizSelektovanih[i] = -1;
                        comboBoxevi[i].SelectedIndex = 0;
                        comboBoxevi[i].Hide();
                        textBoxevi[i].Hide();
                        textBoxevi[i].Text = "1";
                        labeli[i].Hide();
                    }

                    //Filteri za maticnu
                    if (tip == "procesori")
                    {
                        for (int i = 0; i < listaObrisanihMaticnih.Count; i++)
                        {
                            comboBoxevi[1].Items.Add(listaObrisanihMaticnih[i]);
                        }
                        listaObrisanihMaticnih.Clear();

                        string[] atributiSelektovanog = listaKomponenata[selektovanId].atributi.Split('|');
                        string socketSelektovanog = atributiSelektovanog[Array.FindIndex(atributiSelektovanog, row => row.Contains("socket:"))];

                        List<int> listaZaRemovovanjeMaticnih = new List<int>();
                        //Znamo da je index 1 index od maticnih i skipujemo prvi element koji je prazan
                        for (int i = 1; i < comboBoxevi[1].Items.Count; i++)
                        {
                            //string value = comboBoxevi[1].GetItemText(comboBoxevi[1].Items[i]);
                            int idMaticne = Int32.Parse((comboBoxevi[1].Items[i] as ComboboxItem).Value.ToString());
                            if (!listaKomponenata[idMaticne].atributi.Contains(socketSelektovanog))
                            {
                                ComboboxItem item = new ComboboxItem();
                                item.Value = (comboBoxevi[1].Items[i] as ComboboxItem).Value;
                                item.Text = (comboBoxevi[1].Items[i] as ComboboxItem).Text;
                                listaObrisanihMaticnih.Add(item);
                                listaZaRemovovanjeMaticnih.Add(i);
                            }
                        }
                        //Removujemo maticne koje nam ne odgovaraju
                        for (int i = listaZaRemovovanjeMaticnih.Count - 1; i >= 0; i--)
                        {
                            comboBoxevi[1].Items.RemoveAt(listaZaRemovovanjeMaticnih[i]);
                        }
                    }
                    labeli[comboBoxId + 1].Show();
                    comboBoxevi[comboBoxId + 1].Show();
                    textBoxevi[comboBoxId + 1].Show();

                    nizSelektovanih[indexTipa] = selektovanId;
                   
                    submitButton.Hide();
                }
                else
                {
                    nizSelektovanih[indexTipa] = selektovanId;
                    submitButton.Show();
                }
            }
        }

        private void napraviTextBox(int top, int left, string text, int id)
        {
            textBoxevi.Add(new TextBox());
            this.Controls.Add(textBoxevi[id]);
            textBoxevi[id].Top = top;
            textBoxevi[id].Left = left;
            textBoxevi[id].Size = new System.Drawing.Size(23, 23);
            textBoxevi[id].Text = "1";
            textBoxevi[id].KeyPress += keyPressBroj;
            textBoxevi[id].Hide();
        }

        private void napraviLabel(int top, int left, string text, int id)
        {
            labeli.Add(new Label());
            this.Controls.Add(labeli[id]);
            labeli[id].Top = top;
            labeli[id].ForeColor = Color.Black;
            labeli[id].Left = left;
            labeli[id].Size = new System.Drawing.Size(200, 17);
            labeli[id].Text = text;
            labeli[id].Hide();
        }

        private void napraviComboBox(int top, int left, int id, string tip)
        {
            comboBoxevi.Add(new ComboBox());
            this.Controls.Add(comboBoxevi[id]);
            comboBoxevi[id].Top = top;
            comboBoxevi[id].Left = left;
            comboBoxevi[id].Size = new System.Drawing.Size(290, 23);
            comboBoxevi[id].DropDownStyle = ComboBoxStyle.DropDownList;
            comboBoxevi[id].Hide();

            comboBoxevi[id].Items.Add("");
            comboBoxevi[id].SelectedIndex = 0;

            comboBoxevi[id].SelectedIndexChanged += (sender, EventArgs) => { selektovano(sender, EventArgs, id, tip); };
        }

        private void keyPressBroj(object sender, KeyPressEventArgs e)
        {
            if (!char.IsControl(e.KeyChar) && !char.IsDigit(e.KeyChar))
            {
                e.Handled = true;
            }
        }

        private void exitButton_Click(object sender, EventArgs e)
        {
            this.Close();
        }
        Point lastPoint;
        private void Konfigurator_MouseMove(object sender, MouseEventArgs e)
        {
            if (e.Button == MouseButtons.Left)
            {
                this.Left += e.X - lastPoint.X;
                this.Top += e.Y - lastPoint.Y;
            }
        }

        private void Konfigurator_MouseDown(object sender, MouseEventArgs e)
        {
            lastPoint = new Point(e.X, e.Y);
        }

    }
}