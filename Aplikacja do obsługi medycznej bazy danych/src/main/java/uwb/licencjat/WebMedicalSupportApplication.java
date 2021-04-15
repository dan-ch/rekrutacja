package uwb.licencjat;

import org.springframework.boot.SpringApplication;
import org.springframework.boot.autoconfigure.SpringBootApplication;
import org.springframework.context.ConfigurableApplicationContext;
import uwb.licencjat.model.Clinic;
import uwb.licencjat.service.MailService;

import javax.mail.MessagingException;

@SpringBootApplication
public class WebMedicalSupportApplication {

    public static void main(String[] args) throws MessagingException {
        ConfigurableApplicationContext ctx = SpringApplication.run(WebMedicalSupportApplication.class, args);
    }

}
