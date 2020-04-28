#include <stdio.h>
#include <string.h>
#include <iostream>
#include <fstream>
#include <sstream>
#include <algorithm>
#include <windows.h>
#include <conio.h>
#include<cstdlib>
#include<ctime>

#include "rapidxml-1.13/rapidxml.hpp"
#include "rapidxml-1.13/rapidxml_print.hpp"
#include "rapidxml-1.13/rapidxml_utils.hpp"
#include <vector>

using namespace rapidxml;
using namespace std;

char * get_attrvalue(xml_node<>* N, char * text);

string IntToString(int a);

bool pobierz(xml_document <> *doc,char * Nazwa, char * rozszezenie,vector<char> ** buffer);

class MojXML
{
public :
   xml_document<> * XML;
   vector<char> * XMLvect;
   MojXML(char * nazwa,char * rozszezenie);
   ~MojXML();
   xml_node <>* utworz_wierzcholek(string name);
   void dodaj_atrybut(xml_node<>* W,string attr, string value);
   void zmien_atrybut(xml_attribute<> * A, string value);
   void Zapisz_do_pliku(char * nazwa,char * rozszezenie);
   void drukuj();
};


class node
{
private:
   node * prev, * next;
public:
   int id;
   node(int ID);
   node *  get_prev();
   node *  get_next();
   void set_prev(node * NewPrev);
   void set_next(node * NewNext);
   virtual void print();
   ~node();
   virtual string get_name();
};


class pojazd: public node
{
public:
   xml_node<>* Dane;
   xml_node<>* Komentarze;
   xml_node<>* Rezerwacja;
   xml_node<>* Ocena;
   xml_node<>* Wypozyczenia;
   xml_node<>* Wymagania;
   string marka;
   string model;
   string kolor;
   string skrzynia;
   string paliwo;
   int rok_prod;
   int moc;
   float pojemnosc;
   int wiek;
   int rezerwacja;
   int wypozyczony;
   float ocena;
   int ile_kom;
   int ile_wyp;
   int ile_ocen;
   pojazd(xml_node<>* W);
   virtual void zapisz_cos_jescze()=0;
   void print();
   void print2();
   void print_admin();
   string czy_dostepny();
   virtual string get_name();
};


class osobowy: public pojazd
{
public:
   int miejsca;
   osobowy(xml_node<>* W);
   void zapisz_cos_jescze();
   string get_name();
};


class dostawczy: public pojazd
{
public:
   int ladownosc;
   int dl;
   int wys;
   int szer;
   dostawczy(xml_node<>* W);
   void zapisz_cos_jescze();
   string get_name();
};

class motor: public pojazd
{
public:
   string typ;
   motor(xml_node<>* W);
   void zapisz_cos_jescze();
   string get_name();
};


class osoba: public node
{
public:
   xml_node<>* Dane;
   xml_node<>* Rezerwacja;
   xml_node<>* Wypozyczenia;
   string nick;
   string imie;
   string nazwisko;
   string haslo;
   string email;
   int wiek;
   int rez;
   int ile_wyp;
   osoba(xml_node<>* W);
   ~osoba();
   string get_name();
   void print();
   void print_zal();
};


class list
{
private:
   node * first, * last;
   int number;
   void   set_first(node * NewFirst);
   void   set_last(node * NewLast);
   void   set_number(int NewNumber);
   void increase_number();
public:
   node * get_first();
   node * get_last();
   int get_number();
   list();
   node * create_node(int ID);
   void Add2End(node * N);
   void print();
   ~list();
};


class wypozyczalnia
{
public:
   MojXML Dane;
   list *osoby;
   list *pojazdy;
   osoba *zal;
   xml_node<> *zal_xml;
   wypozyczalnia();
   osoba *Logowanie();
   void dodaj_osobe();
   void dodaj_pojazd();
   void drukuj_pojazdy();
   void wyszukaj_pojazdy();
   void wypozycz();
   string generator();
   void oddaj();
   void drukuj_moje();
   void drukuj_dostepne();
   void drukuj_pojazdy_admin();
   void edytuj_dane();
   void co_wyp();
   void reset_hasla();
   void dodaj_komentarz();
   void dodaj_ocene();
   void wyszukaj_osobe();
   void historia();
   void print_Menu_start(int i);
   void print_Menu_zalogowany(int i);
   void print_Menu_admin(int i);
   void printMenu(bool zielone, string s);
   void wywolaj_start();
   void wywolaj_zalogowany();
   void wywolaj_admin();
};
