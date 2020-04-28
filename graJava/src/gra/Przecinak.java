package gra;

import java.awt.Color;
import java.awt.Font;
import java.awt.Graphics;
import java.awt.Graphics2D;
import java.awt.Rectangle;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.awt.event.KeyEvent;
import java.awt.event.KeyListener;
import java.awt.event.MouseEvent;
import java.awt.event.MouseListener;
import java.util.ArrayList;
import java.util.Random;
import javax.swing.*;


public class Przecinak implements ActionListener, MouseListener, KeyListener {

    public static Przecinak Przecinak;

    public final int WIDTH = 1200, HEIGHT = 800;

    public Renderer renderer;

    public Rectangle hero;

    public ArrayList<Ball> balls;

    public int yMotion, score, xMontion, lives, level;

    public boolean gameOver, started, hit, win;

    public Random rand;

    public Rectangle giwera;

    public Przecinak() {
        JFrame jframe = new JFrame();
        Timer timer = new Timer(25, this);

        renderer = new Renderer();
        rand = new Random();

        jframe.add(renderer);
        jframe.setTitle("Przecinak");
        jframe.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        jframe.setSize(WIDTH, HEIGHT);
        jframe.addMouseListener(this);
        jframe.addKeyListener(this);
        jframe.setResizable(false);
        jframe.setVisible(true);

        hero = new Rectangle(WIDTH / 2 - 20, HEIGHT - 160, 40, 40);
        balls = new ArrayList<Ball>();
        level = 1;
        lives = 3;
        score = 0;
        hit = false;
        win = false;

        giwera = new Rectangle(WIDTH / 2 - 5, HEIGHT - 120, 10, 780);

        addBall(true, null);

        timer.start();
    }

    public void addBall(boolean start, Ball b) {
        int radius = 15;

        if (start) {
            balls.add(new Ball(WIDTH / 2 - radius * level / 2, 80, radius * level, radius * level, level));
        } else {
            int temp = (b.lvl - 1);
            balls.add(new Ball(b.x - 50, b.y, radius * temp, radius * temp, temp));
            b.x = b.x + 50;
            b.height = radius * temp;
            b.width = radius * temp;
            b.lvl = temp;
        }

    }

    public void repaint(Graphics g) {
        g.setColor(Color.blue.darker().darker());
        g.fillRect(0, 0, WIDTH, HEIGHT);

        g.setColor(Color.gray);
        g.fillRect(giwera.x, giwera.y, giwera.width, giwera.height);

        g.setColor(Color.DARK_GRAY);
        g.fillRect(0, HEIGHT - 120, WIDTH, 120);

        g.setColor(Color.red);
        g.fillRect(hero.x, hero.y, hero.width, hero.height);

        for (Ball b : balls) {
            paintBall(g, b);
        }

        g.setColor(Color.white);
        g.setFont(new Font("Arial", 1, 30));

        if (!started) {
            g.drawString("Click to start!", 500, HEIGHT / 2 - 50);
        }

        if (hit) {
            if (gameOver) {
                g.drawString("Game Over!", 520, HEIGHT / 2 - 100);
            } else
                g.drawString("Zostales trafiony!", 480, HEIGHT / 2 - 100);
        }

        if (win) {
            g.drawString("Wygrales!", 540, HEIGHT / 2 - 100);
        }


        if (!gameOver && started) {
            g.drawString("Score:" + String.valueOf(score), WIDTH - 160, 40);
            g.drawString("Lives:" + String.valueOf(lives), 10, 40);
        }
    }

    public void paintBall(Graphics g, Ball b) {
        Graphics2D g2 = (Graphics2D) g;
        switch (b.lvl) {
            case 1:
                g2.setPaint(Color.red.darker());
                break;
            case 2:
                g2.setPaint(Color.yellow.darker());
                break;
            case 3:
                g2.setPaint(Color.pink.darker());
                break;
            case 4:
                g2.setPaint(Color.red.darker());
                break;
            case 5:
                g2.setPaint(Color.white.darker());
                break;
            default:
                g2.setPaint(Color.black.darker());
        }
        g2.fill(b);
    }

    public void shoot() {
        if (!started) {
            started = true;
            gameOver = false;
            win = false;
        } else if (!gameOver) {

            if (giwera.y >= HEIGHT - 120) {
                giwera.x = hero.x + 20 - giwera.width / 2;
                yMotion -= 20;
            }
        }
    }


    @Override
    public void actionPerformed(ActionEvent e) {
        int speed = 1;

        if (hit) {
            balls.clear();
            addBall(true, null);
            hero.x = WIDTH / 2 - 20;
            hero.y = HEIGHT - 160;
            hit = false;
        }


        if (started) {
            for (int i = 0; i < balls.size(); i++) {
                Ball b = balls.get(i);

                b.y += speed + (5 - b.lvl);
                b.x += b.xMove;
                if (b.move >= 0) {
                    b.y -= 2 * (speed + (5 - b.lvl));
                    b.move--;
                } else if (b.y + b.height >= 675)
                    b.jump();

                if (b.x <= 0 || b.x >= WIDTH - b.width * 2) {
                    b.xMove = -b.xMove;
                }

                if (b.lvl == 0)
                    balls.remove(i);

            }


            for (Ball b : balls) {
                if (b.intersects(giwera)) {
                    addBall(false, b);
                    giwera.y = HEIGHT - 120;
                    yMotion = 0;
                    b.xMove += (rand.nextInt(3) + 1) * (5 - b.lvl);
                    balls.get(balls.size() - 1).xMove -= (rand.nextInt(3) + 1) * (5 - b.lvl);
                    score++;
                    break;
                }
                if (b.intersects(hero)) {
                    lives--;
                    started = false;
                    if (lives == 0) {
                        level = 1;
                        score = 0;
                        lives = 3;
                        gameOver = true;
                        hit = true;
                    } else {
                        hit = true;
                        yMotion = 0;
                    }
                }
            }

            if (balls.size() == 0) {
                level++;
                started = false;
                balls.clear();
                addBall(true, null);
                hero.x = WIDTH / 2 - 20;
                hero.y = HEIGHT - 160;
            }

            if (giwera.y <= 0) {
                giwera.y = HEIGHT - 120;
                yMotion = 0;
            }


            giwera.y += yMotion;
            hero.x += xMontion;

            renderer.repaint();
        } else if (level >= 6) {
            level = 1;
            score = 0;
            lives = 3;
            win = true;
            started = false;
            balls.clear();
            addBall(true, null);
            renderer.repaint();
        }
    }


    @Override
    public void keyTyped(KeyEvent e) {

    }

    @Override
    public void keyPressed(KeyEvent e) {
        if (e.getKeyCode() == KeyEvent.VK_LEFT)
            xMontion = -4;
        else if (e.getKeyCode() == KeyEvent.VK_RIGHT)
            xMontion = +4;

    }

    @Override
    public void keyReleased(KeyEvent e) {
        if (e.getKeyCode() == KeyEvent.VK_SPACE) {
            shoot();
        }
        if (e.getKeyCode() == KeyEvent.VK_LEFT)
            xMontion = 0;
        else if (e.getKeyCode() == KeyEvent.VK_RIGHT)
            xMontion = 0;
    }

    @Override
    public void mouseClicked(MouseEvent e) {

    }

    @Override
    public void mousePressed(MouseEvent e) {

    }

    @Override
    public void mouseReleased(MouseEvent e) {

    }

    @Override
    public void mouseEntered(MouseEvent e) {

    }

    @Override
    public void mouseExited(MouseEvent e) {

    }
}

